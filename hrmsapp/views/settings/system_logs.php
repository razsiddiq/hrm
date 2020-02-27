<?php $session = $this->session->userdata('username');?>
<?php if(in_array('system-logs',role_resource_ids())) {?>
<div class="row m-b-1 animated fadeInRight"><div class="col-lg-12"> 
<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">							 
							<strong>Filter Logs</strong>							
							</h5>						
						</div>         
            <div class="panel-body">
			<form action="" method="post">
              <div class="row">            
				
              <div class="col-md-3">
                <div class="form-group">
               
				  <div class="input-group date form_month_year" data-date="" data-date-format="yyyy MM"  data-link-format="yyyy MM">
                    <input class="form-control" placeholder="Select Month" name="month_year" size="16" id="month_year"  type="text" value="<?php echo$month_year;?>" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					
                </div>
				

                </div>
              </div>

          		<div class="col-md-3">
              		<div class="form-group">
						<select class="form-control" id="system_module" name="system_module" data-plugin="select_hrm" data-placeholder="Choose the action..." >
							<option value="">All</option>
							<?php 
							for ($i=0; $i < count($action_list); $i++) { 
							?>
								<option value="<?=$action_list[$i]['system_module']?>" <?php if(@$system_module==$action_list[$i]['system_module']){ echo 'selected';}?> ><?=$action_list[$i]['system_module']?></option>
							<?php }?>
						</select>
					</div>
				</div>
			  
			  
			 <div class="col-md-3">
                  <div class="form-group"> &nbsp;
                    <button type="submit"  class="btn bg-teal-400 save mr-20">Filter</button>					
                  </div>
                </div>
              </div>
              
			  </form>
			  
			  </div>
          
          
    
    </div>
  

<pre class=" language-javascript"><code class=" language-javascript" data-language="Logs">

 
<span class="token comment" spellcheck="true">// # List of System Logs
</span><span class="token comment" spellcheck="true">// ------------------------------</span>

<?php 
if($system_logs){
foreach($system_logs as $logs){
$logs_action=explode('/',$logs->system_module);	
?>
<span class="token punctuation">[</span><?php echo $this->Xin_model->set_date_format($logs->update_date).' '.format_date('H:i:s',$logs->updated_date);?><span class="token punctuation">]</span> <span class="token punctuation">[</span><?php echo $logs_action[0];?><span class="token punctuation">]</span> <span class="token punctuation">[</span><?php echo $logs->module_type;?><span class="token punctuation">]</span> <?php echo $logs->details;?> <span class="token operator"> </span>
<?php } }else {?>
No Logs Found...
<?php } ?>



</code></pre>
</div>
</div>
<?php } else { ?>
		<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title text-danger">
							 
							 <?php echo $this->lang->line('xin_permission');?>
							
							</h5>
				
						</div>
				</div>

		<?php } ?>
