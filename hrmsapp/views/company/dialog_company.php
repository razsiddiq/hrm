<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['company_id']) && $_GET['data']=='company'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_company');?></h4>
</div>

<?php if(in_array('3e',role_resource_ids())) {?>
<form class="m-b-1" action="<?php echo site_url("company/update").'/'.$company_id; ?>" method="post" name="edit_company" id="edit_company">
<?php } ?>
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $_GET['company_id'];?>">
  <input type="hidden" name="ext_name" value="<?php echo $name;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">     
		    <div class="form-group">       
				  <div class="row">
            <div class="col-md-6">
              <label for="company_name"><?php echo $this->lang->line('xin_company_name');?><?php echo REQUIRED_FIELD;?></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_name');?>" name="name" type="text" value="<?php echo $name;?>">
            </div>
            <div class="col-md-6">
              <label for="license_no"><?php echo $this->lang->line('xin_licence_no');?><?php echo REQUIRED_FIELD;?></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_licence_no');?>" name="license_no" type="text"  value="<?php echo $license_no;?>">
            </div>
          </div>				  
        </div>
		
        <div class="form-group">
          <div class="row">
            <div class="col-md-6">              
              <label for="trading_name"><?php echo $this->lang->line('xin_company_trading');?></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_trading');?>" name="trading_name" type="text" value="<?php echo $trading_name;?>">
            </div>
            <div class="col-md-6">
              <label for="register_no"><?php echo $this->lang->line('xin_company_registration');?></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_registration');?>" name="register_no" type="text" value="<?php echo $registration_no;?>">
            </div>
          </div>
        </div>
			
        <div class="form-group">
          <div class="row">
            <div class="col-md-6">                     
					    <label for="contract_start_date"><?php echo $this->lang->line('xin_constartdate');?></label>
              <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                <input class="form-control" placeholder="<?php echo $this->lang->line('xin_constartdate');?>" name="contract_start_date" size="16" type="text" value="<?php echo format_date('d F Y',$contract_start_date);?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>					
              </div>
						</div>
            
            <div class="col-md-6">
              <label for="contract_end_date"><?php echo $this->lang->line('xin_conenddate');?></label>                    
					    <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                <input class="form-control" placeholder="<?php echo $this->lang->line('xin_conenddate');?>" name="contract_end_date" size="16" type="text" value="<?php echo format_date('d F Y',$contract_end_date);?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>					
              </div>
				    </div>
          </div>
        </div>			
	
        <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <label for="email"><?php echo $this->lang->line('xin_email');?><?php echo REQUIRED_FIELD;?></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_email');?>" name="email" type="email" value="<?php echo $email;?>">
            </div>
            <div class="col-md-6">
              <label for="website"><?php echo $this->lang->line('xin_website');?></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_website_url');?>" name="website" value="<?php echo $website_url;?>" type="text">
            </div>
          </div>
        </div>

        <div class="form-group">
          <h6><?php echo $this->lang->line('xin_company_logo');?></h6>		  
          <input type="file" class="file-input1" name="logo">
          <span class="help-block"><?php echo $this->lang->line('xin_company_file_type');?></span>			  
          <?php if($logo!='' || $logo!='no-file'){?>
            <div class="avatar box-48 mt-10 col-lg-4">
              <img width="100%" class="b-a-radius-circle" src="<?php echo site_url();?>uploads/company/<?php echo $logo;?>" alt="">
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">			  
          <div class="row">
            <div class="col-md-6">
              <label for="license_expiry_date"><?php echo $this->lang->line('xin_licexpirydate');?><?php echo REQUIRED_FIELD;?></label>					
              <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy"  data-link-format="yyyy-mm-dd">
                <input class="form-control" placeholder="<?php echo $this->lang->line('xin_licexpirydate');?>" name="license_expiry_date" size="16" type="text" value="<?php echo format_date('d F Y',$license_expiry_date);?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>					
              </div>				
            </div>
            
            <div class="col-md-6">
              <label for="email"><?php echo $this->lang->line('xin_company_type');?><?php echo REQUIRED_FIELD;?></label>
              <select class="form-control" name="company_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_company_type');?>">
                <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                <?php foreach($get_company_types as $ctype) {?>
                <option value="<?php echo $ctype->type_id;?>" <?php if($type_id==$ctype->type_id){?> selected="selected" <?php } ?>> <?php echo $ctype->type_name;?></option>
                <?php } ?>
              </select>                    
            </div>
          </div>	  
				</div>
        
        <div class="form-group">
          <label for="address"><?php echo $this->lang->line('xin_address');?><?php echo REQUIRED_FIELD;?></label>
          <div class="row">
            <div class="col-xs-6">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address_1" type="text" value="<?php echo $address_1;?>">
            </div>
            <div class="col-xs-6">
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address_2" type="text" value="<?php echo $address_2;?>">
            </div>
          </div>
          <br>
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
          <br>
          <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
            <?php foreach($all_countries as $country) {?>
            <option value="<?php echo $country->country_id;?>" <?php if($countryid==$country->country_id):?> selected="selected"<?php endif;?>> <?php echo $country->country_name;?></option>
            <?php } ?>
          </select>
        </div>
		
		    <div class="form-group">
          <div class="row">           
			      <?php $mobile_phone=@explode('-',@$contact_number);?>
            <div class="col-md-12">
              <label for="contact_number"><?php echo $this->lang->line('xin_contact_number');?><?php echo REQUIRED_FIELD;?></label>
              <div class="clearfix"></div>
	            <div class="input-group">
				      <span class="input-group-addon-custom">	
                <select class="form-control change_country_code1 js-example-templating_diag" name="country_code">
                  <?php foreach(phone_numbers_code() as $keys=>$phone_code){?>
                  <option <?php if($keys==@$mobile_phone[0]){echo 'selected';}?> value="<?php echo $keys; ?>-" data-len ="<?php echo $phone_code['length'];?>"   rel="<?php echo $phone_code['country_name'];?>"><?php echo $keys; ?></option>
                  <?php } ?>
                </select>		  
			        </span>
              <input class="form-control c_num" title="<?php echo $this->lang->line('xin_use_numbers');?>" placeholder="<?php echo $this->lang->line('xin_e_details_mobile');?>" name="contact_number" type="text" pattern="\d*" maxlength="<?php echo MAX_PHONE_DIGITS;?>"  value="<?php echo @$mobile_phone[1];?>">
			      </div>		  
          </div>
        </div>
      </div>
		
		  <div class="form-group">
	      <h6><?php echo $this->lang->line('xin_company_trade_copy');?></h6>		  
			  <input type="file" class="file-input1" name="trade_copy">
			  <span class="help-block"><?php echo $this->lang->line('xin_e_details_d_type_file');?></span>
			  <?php if($trade_copy!='' || $trade_copy!='no-file'){			  
          $file_parts = pathinfo($trade_copy);          
          if($file_parts['extension']!='pdf'){ ?>          
          <a href="<?php echo site_url().'uploads/company/'.$trade_copy;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded">
            <div class="thumbnail" style="max-width: 193.5px;">
							<div class="thumb">
								<img style="height: 7em;width: 10em;" src="<?php echo site_url().'uploads/company/'.$trade_copy;?>" alt="">
                <div class="caption-overflow">
                  <span>										
                  </span>
                </div>
              </div>
						</div>
          </a>		
			    <?php }else{ ?>        
		      <a target="_blank" href="<?php echo site_url().'uploads/company/'.$trade_copy;?>" class="btn border-white text-white btn-flat btn-icon btn-rounded">
            <div class="thumbnail" style="max-width: 4em;">		  
              <div class="thumb">
                <img src="<?php echo base_url().'uploads/pdf-preview.jpg';?>" alt="">              
              </div>
						</div>
          </a>
		    	<?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <?php if(in_array('3e',role_resource_ids())) {?>
    <button type="submit" class="btn bg-teal-400 save"><?php echo $this->lang->line('xin_update');?></button>
    <?php } ?>
  </div>
</form>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/fileupload.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	 $(".js-example-templating_diag").select2({
			templateResult: formatState,
			matcher: matchStart,
			dropdownAutoWidth : true
		});

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

  	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));	
	  
    $('[data-popup="lightbox"]').fancybox({	 
    autoCenter: true,  
    afterShow: function(){
      var win=null;	
      var content = $('.fancybox-inner');
      $('.fancybox-wrap')
      // append print button
      .append('<div title="Download" id="fancy_download" style="position: absolute;right: -5em;top: 4em;z-index:9999;background: #249e92;padding: 10px;border: 1px solid #ffff;"><a href='+site_url+'download?type=company&filename='+extract_name($(this).attr('href'))+'><i class="icon-download" style="font-size: 1.5em;color: white;cursor: pointer;"></i></a></div>');
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
      }, //  afterShow  
    }); // fancybox
 
	
    $(".change_country_code1").change(function () {
      //var rel=<?php echo MAX_PHONE_DIGITS;?>;
      var rel=$(this).find(':selected').data('len');
      $('.c_num').val('');
      $('.c_num').attr('maxlength',rel);
    });
  /* Edit data */
  $("#edit_company").submit(function(e){
    var fd = new FormData(this);
    var obj = $(this), action = obj.attr('name');
    fd.append("is_ajax", 2);
    fd.append("edit_type", 'company');
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
            $('#xin_table').dataTable().api().ajax.reload(function(){ 
          toastr.success(JSON.result);
        }, true);
          $('.edit-modal-data').modal('toggle');
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

<?php } else if(isset($_GET['jd']) && $_GET['data']=='view_company' && isset($_GET['company_id']) ){ ?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_view_company');?></h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">  
        <div class="form-group">                 			  
				  <div class="row">
            <div class="col-md-6">
              <label for="company_name"><?php echo $this->lang->line('xin_company_name');?></label>
               <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $name;?>">
            </div>
            <div class="col-md-6">                     
              <label for="license_no"><?php echo $this->lang->line('xin_licence_no');?></label>
					    <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $license_no;?>">
            </div>
          </div>
				</div>				
				<div class="form-group">
          <div class="row">
            <div class="col-md-6">                     
					    <label for="trading_name"><?php echo $this->lang->line('xin_company_trading');?></label>       
              <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $trading_name;?>">
            </div>
            <div class="col-md-6">
              <label for="register_no"><?php echo $this->lang->line('xin_company_registration');?></label>
              <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $registration_no;?>">
            </div>
          </div>
        </div>				
				<div class="form-group">
          <div class="row">
            <div class="col-md-6">                     
					    <label for="contract_start_date"><?php echo $this->lang->line('xin_constartdate');?></label>                     
              <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $contract_start_date;?>">							 
            </div>
            <div class="col-md-6">
              <label for="contract_end_date"><?php echo $this->lang->line('xin_conenddate');?></label>                    
					    <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $contract_end_date;?>">
				    </div>
          </div>
        </div>	
        <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <label for="email"><?php echo $this->lang->line('xin_email');?></label>
              <input class="form-control" readonly="readonly" style="border:0;" type="email" value="<?php echo $email;?>">
            </div>
            <div class="col-md-6">
              <label for="website"><?php echo $this->lang->line('xin_website');?></label>
              <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $website_url;?>">
            </div>
          </div>
        </div>
        <div class="form-group">
          <h6>
            <label><?php echo $this->lang->line('xin_company_logo');?></label>
          </h6>
          <?php if($logo!='' || $logo!='no-file'){?>
          <div class="avatar box-48 mr-0-5 col-lg-4"> <img width="100%" class="b-a-radius-circle" src="<?php echo site_url();?>uploads/company/<?php echo $logo;?>" class="img-circle img-sm img-responsive" alt=""></a> </div>
          <?php } ?>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">				  
				  <div class="row">
            <div class="col-md-6">
              <label for="license_expiry_date"><?php echo $this->lang->line('xin_licexpirydate');?></label>					
					    <input class="form-control" readonly="readonly" style="border:0;" type="email" value="<?php echo $license_expiry_date;?>">				
            </div>
            <div class="col-md-6">
              <label for="email"><?php echo $this->lang->line('xin_company_type');?></label>                     
			        <input class="form-control" readonly="readonly" style="border:0;" type="email" value="<?php foreach($get_company_types as $ctype) {if($type_id==$ctype->type_id){ echo $ctype->type_name; }  }?>"/>
            </div>
          </div>
				</div>
        <div class="form-group">
          <label for="address"><?php echo $this->lang->line('xin_address');?></label>
          <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $address_1;?>">
          <br>
          <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $address_2;?>">
          <br>
          <div class="row">
            <div class="col-xs-5">
              <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $city;?>">
            </div>
            <div class="col-xs-4">
              <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $state;?>">
            </div>
            <div class="col-xs-3">
              <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $zipcode;?>">
            </div>
          </div>
          <br>
          <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php foreach($all_countries as $country) {?><?php if($countryid==$country->country_id):?><?php echo $country->country_name;?><?php endif;?><?php } ?>">
        </div>
		
	    	<div class="form-group">
          <div class="row">           
			      <?php $mobile_phone=@explode('-',@$contact_number);?>
            <div class="col-md-12">
              <label for="contact_number"><?php echo $this->lang->line('xin_contact_number');?></label>         
			        <input class="form-control" readonly="readonly" style="border:0;" type="text" value="<?php echo $contact_number;?>">
            </div>
          </div>
        </div>
		
		    <div class="form-group">
          <h6>
            <label><?php echo $this->lang->line('xin_company_trade_copy');?></label>
          </h6>
          <?php if($trade_copy!='' || $trade_copy!='no-file'){
          $file_parts = pathinfo($trade_copy);			 
          if($file_parts['extension']!='pdf'){ ?>          
          <a href="<?php echo site_url().'uploads/company/'.$trade_copy;?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><div class="thumbnail" style="max-width: 193.5px;">
              <div class="thumb">
                <img style="height: 7em;width: 10em;" src="<?php echo site_url().'uploads/company/'.$trade_copy;?>" alt="">
                <div class="caption-overflow">
                  <span>                  
                  </span>
                </div>
              </div>
            </div>
          </a>          
          <?php  } else{ ?>        
		      <a target="_blank" href="<?php echo site_url().'uploads/company/'.$trade_copy;?>" class="btn border-white text-white btn-flat btn-icon btn-rounded">
            <div class="thumbnail" style="max-width: 4em;">		  
              <div class="thumb">
                <img src="<?php echo base_url().'uploads/pdf-preview.jpg';?>" alt="">              
              </div>
						</div>
          </a>
			    <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>
</form>
<script>
$(document).ready(function(){
  $('[data-popup="lightbox"]').fancybox({	 
      autoCenter: true,  
      afterShow: function(){
      var win=null;
    
      var content = $('.fancybox-inner');
      $('.fancybox-wrap')
      // append print button
      .append('<div title="Download" id="fancy_download" style="position: absolute;right: -5em;top: 4em;z-index:9999;background: #249e92;padding: 10px;border: 1px solid #ffff;"><a href='+site_url+'download?type=company&filename='+extract_name($(this).attr('href'))+'><i class="icon-download" style="font-size: 1.5em;color: white;cursor: pointer;"></i></a></div>');
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
    }, //  afterShow
  }); // fancybox   
 });
</script>
<?php }
?>
<script>
function extract_name(name){
  var lastIndex = name.lastIndexOf("/");
  return  name.substring(lastIndex + 1);
}
</script>