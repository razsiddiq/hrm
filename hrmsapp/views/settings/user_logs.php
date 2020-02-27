<?php
/* Database Backup Log view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php if(in_array('59',role_resource_ids())) {?>
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
                  <div class="form-group"> &nbsp;
                    <button type="submit"  class="btn bg-teal-400 save mr-20">Filter</button>					
                  </div>
                </div>
              </div>
              
			  </form>
			  
			  </div>
          
          
    
    </div>
  

<pre class=" language-javascript"><code class=" language-javascript" data-language="Logs">

 
<span class="token comment" spellcheck="true">// # List of Activity Logs
</span><span class="token comment" spellcheck="true">// ------------------------------</span>

<?php 
if($user_logs){
foreach($user_logs as $logs){
$logs_action=explode('-',$logs->module);	
$result = $this->Xin_model->read_user_info($logs->user_id);
// if($result[0]->email == CC_MAIL)
// 	continue;
?>
<span class="token punctuation">[</span><?php echo $this->Xin_model->set_date_format($logs->updated_date).' '.format_date('H:i:s',$logs->updated_date);?><span class="token punctuation">]</span> <span class="token punctuation">[</span><?php echo $logs_action[0];?><span class="token punctuation">]</span> <?php echo $logs->action;?><span class="token operator"> - </span><span class="token number">By <?php echo change_fletter_caps($result[0]->first_name.' '.$result[0]->last_name);?></span><span style="display:none;"><?=base64_decode($logs->datas); ?></span>
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
