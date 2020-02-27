<?php if(in_array('54v',role_resource_ids())) {?>	
<?php
/* Constants view
*/
?>
<?php $session = $this->session->userdata('username');


if(in_array('54a',role_resource_ids())) {
	$class='col-md-7';
}else{
	$class='col-md-12';
}
?>



<div class="row">
						<div class="col-md-12">
							<div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title">
									</h6>
								
								</div>

								<div class="panel-body">
									<div class="tabbable nav-tabs-vertical nav-tabs-left">
										<ul class="nav nav-tabs nav-tabs-highlight">
					<li id="config_8" class="active"><a href="#contract" data-toggle="tab"><i class="icon-pencil7 position-left"></i>Contract Type</a></li>
					<li id="config_7"><a href="#qualification" data-toggle="tab"><i class="icon-graduation2 position-left"></i>Qualification</a></li>
					<li id="config_9"><a href="#document_type" data-toggle="tab"><i class="icon-file-plus2 position-left"></i>Document Type</a></li>
					<li id="config_9a"><a href="#visa_under" data-toggle="tab"><i class="icon-newspaper2 position-left"></i>Visa Under & Medical Card Type</a></li>
					<li id="config_10"><a href="#award_type" data-toggle="tab"><i class="icon-calendar3 position-left"></i>Award Type</a></li>
					<li id="config_11"><a href="#leave_type" data-toggle="tab"><i class="icon-bed2 position-left"></i>Leave Type</a></li>
					
					<li id="config_12"><a href="#warning_type" data-toggle="tab"><i class="icon-megaphone position-left"></i>Warning Type</a></li>
					<li id="config_13"><a href="#termination_type" data-toggle="tab"><i class="icon-cross2 position-left"></i>Termination Type</a></li>					
					<li id="config_17"><a href="#expense_type" data-toggle="tab"><i class="icon-chart position-left"></i>Expense Type</a></li>	
					<li id="config_12"><a href="#job_type" data-toggle="tab"><i class="icon-book position-left"></i>Job Type</a></li>		
					<li id="config_21"><a href="#company_type" data-toggle="tab"><i class="icon-office position-left"></i>Company Type</a></li>
					<li id="config_15"><a href="#exit_type" data-toggle="tab"><i class="icon-exit2 position-left"></i>Employee Exit Type</a></li>	
					<li id="config_18"><a href="#travel_arr_type" data-toggle="tab"><i class="icon-airplane4 position-left"></i>Travel Arrangement Type</a></li>	
					<li id="config_16"><a href="#payment_method" data-toggle="tab"><i class="icon-cash3 position-left"></i>Payment Methods</a></li>	
					<li id="config_19"><a href="#currency_type" data-toggle="tab"><i class="icon-coin-dollar position-left"></i>Currency</a></li>	
					<li id="config_20"><a href="#salary_type" data-toggle="tab"><i class="icon-calculator4 position-left"></i>Salary Type</a></li>
<li id="config_21"><a href="#tax_type" data-toggle="tab"><i class="icon-percent position-left"></i>Tax Type</a></li>
<li id="config_22"><a href="#salary_field_structure" data-toggle="tab"><i class="icon-newspaper position-left"></i>Salary Field Structure</a></li>
<li id="config_23"><a href="#ob_type" data-toggle="tab"><i class="icon-calendar3 position-left"></i>OB Type</a></li>				
<li id="config_24"><a href="#country_list" data-toggle="tab"><i class="icon-earth position-left"></i>Country Lists</a></li>				
	</ul>

										<div class="tab-content">
											
											<div class="tab-pane active has-padding animated fadeInRight" id="contract">
											   
											   <div class="panel panel-flat">
						 		
								
								<?php if(in_array('54a',role_resource_ids())) {?>	
								<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Contract Type
									</h6>
									
								</div>
								<?php } ?>
								
								
								
     <div class="row">
	 <div class="panel-body">
	 
	 <?php if(in_array('54a',role_resource_ids())) {?>	
        <div class="col-md-5">
          

            <form class="m-b-1 add" id="contract_type_info" action="<?php echo site_url("settings/contract_type_info") ?>" name="contract_type_info" method="post">
              <div class="form-group">
                <label for="name">Contract Type</label>
                <input type="text" class="form-control" name="contract_type" placeholder="Enter Contract Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
         
        </div>
	
     
	<?php } ?>
	 
	 
        <div class="<?php echo $class;?>">
		
	
          	   	<div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Contract Type
									</h6>							
								</div>
          
            <div class="panel-body">
              <table class="table table-striped" id="xin_table_contract_type">
                <thead>
                  <tr>
                    
                    <th>Contract Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      
   </div>
</div>

   </div>
  
											
								            </div>
								
								
								
								
								
								<div class="tab-pane has-padding animated fadeInRight" id="qualification">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>	
        <div class="col-md-5">
          	   	<div class="panel panel-flat">
			
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Education Level
									</h6>
								
								</div>
			
            <div class="panel-body">
            <form class="m-b-1 add" id="edu_level_info" action="<?php echo site_url("settings/edu_level_info") ?>" name="edu_level_info" method="post">
              <div class="form-group">
                <label for="name">Education Level</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Education Level">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
			
			</div>
          </div>
        </div>
		
		
	
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
	
           	<div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Education Level
									</h6>
								
								</div>
             <div class="panel-body">
            <div  data-pattern="priority-columns">
              <table class="table table-striped" id="xin_table_education_level">
                <thead>
                  <tr>
                   
                    <th>Education Level</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
			</div>
          </div>
        </div>
      </div>
      <div class="row">
	  
	   	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
           	<div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Language
									</h6>
								
								</div>
      <div class="panel-body">
            <form class="m-b-1 add" id="edu_language_info" action="<?php echo site_url("settings/edu_language_info") ?>" name="edu_language_info" method="post">
              <div class="form-group">
                <label for="name">Language</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Language">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
        </div>
		</div>
        </div>
      		
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Language
									</h6>
								
								</div>
     <div class="panel-body">
            <div  data-pattern="priority-columns">
              <table class="table table-striped" id="xin_table_qualification_language">
                <thead>
                  <tr>
            
                    <th>Language</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div></div>
        </div>
      </div>
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Skill
									</h6>
								
								</div>
         <div class="panel-body">
            <form class="m-b-1 add" id="edu_skill_info" action="<?php echo site_url("settings/edu_skill_info") ?>" name="edu_skill_info" method="post">
              <div class="form-group">
                <label for="name">Skill</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Skill">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
			</div>
			
          </div>
        </div>
        
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Skill
									</h6>
								
								</div>
          <div class="panel-body">
            <div  data-pattern="priority-columns">
              <table class="table table-striped" id="xin_table_qualification_skill">
                <thead>
                  <tr>
                 
                    <th>Skill</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
         
</div>
		 </div>
        </div>
      </div>
    </div>
 
								</div>
								
								<div class="tab-pane has-padding animated fadeInRight" id="document_type">
								  <div  class="box box-block bg-white">
      <div class="row">
	  
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Document Type
									</h6>
								
								</div>
<div class="panel-body">
            <form class=" " id="document_type_info" action="<?php echo site_url("settings/document_type_info") ?>" name="document_type_info" method="post">
              <div class="form-group">
                <label for="name">Document Type</label>
                <input type="text" class="form-control" name="document_type" placeholder="Enter Document Type">
              </div>
			  <div class="form-group">
                <label for="name">Supported Countries (For Reports Filter)</label>
                
				
				 <select name="country_doc[]"  multiple class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
            <option value=""></option>
             <?php foreach($all_countries as $country) {?>
                    <option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                    <?php } ?>
          </select>
		  
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div></div>
        </div>
               
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
         <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Document Type
									</h6>
								
								</div>
<div class="panel-body">

              <table class="table table-striped" id="xin_table_document_type">
                <thead>
                  <tr>
           
                    <th>Document Type</th>
					<th>Country Support</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="visa_under">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Visa Under
									</h6>
								
								</div>
<div class="panel-body">
       
            <form class="m-b-1 add customformadd" id="visa_under_info" action="<?php echo site_url("settings/visa_under_info") ?>" name="visa_under_info" method="post">
              <div class="form-group">
                <label for="name">Visa Under</label>
                <input type="text" class="form-control" name="visa_under" placeholder="Enter Visa Under">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div></div>
        </div>
        
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Visa Under
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_visa_under" >
                <thead>
                  <tr>
                  
                    <th>Visa Under</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


  <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Medical Card Type
									</h6>
								
								</div>
<div class="panel-body">
           
            <form class="m-b-1 add customformadd" id="medical_card_type_info" action="<?php echo site_url("settings/medical_card_type_info") ?>" name="medical_card_type_info" method="post">
              <div class="form-group">
                <label for="name">Medical Card Type</label>
                <input type="text" class="form-control" name="medical_card_type" placeholder="Enter Medical Card Type">
              </div>
			  
			  <div class="form-group">
                <label for="name">Number Of Dependant</label>
				
				<select name="no_of_dependant" data-plugin="select_hrm" data-placeholder="Choose No Of Dependant..." class="form-group"> 
				<option value="0">0 Dependant</option>
				<option value="1">1 Dependant</option>
				<option value="2">2 Dependant</option>
				<option value="3">3 Dependant</option>
				<option value="4">4 Dependant</option>
				<option value="5">5 Dependant</option>
				<option value="6">6 Dependant</option>
				<option value="7">7 Dependant</option>
				<option value="8">8 Dependant</option>
				<option value="9">9 Dependant</option>
				<option value="10">10 Dependant</option>
				
				</select>
              
              </div>
			  
			  
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div></div>
        </div>
        
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
               <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Medical Card Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_medical_card_type">
                <thead>
                  <tr>
                 
                    <th>Medical Card Type</th>
					<th>Number Of Dependant</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="award_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Award Type
									</h6>
								
								</div>
			<div class="panel-body">
  
            <form class="m-b-1 add" id="award_type_info" action="<?php echo site_url("settings/award_type_info") ?>" name="award_type_info" method="post">
              <div class="form-group">
                <label for="name">Award Type</label>
                <input type="text" class="form-control" name="award_type" placeholder="Enter Award Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div>
        </div>
		</div>
        
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
	
              <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Award Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_award_type">
                <thead>
                  <tr>
                   
                    <th>Award Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="leave_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Leave Type
									</h6>
								
								</div>
			<div class="panel-body">
    
            <form class="m-b-1 add" id="leave_type_info" action="<?php echo site_url("settings/leave_type_info") ?>" name="leave_type_info" method="post">
              <div class="form-group">
                <label for="name">Leave Type</label>
                <input type="text" class="form-control" name="leave_type" placeholder="Enter Leave Type">
              </div>
              <div class="form-group">
                <label for="name">Days per Year</label>
                <input type="text" class="form-control" name="days_per_year" placeholder="Days per Year">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div>
        </div> </div>
        
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
               <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Leave Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_leave_type">
                <thead>
                  <tr>
                
                    <th>Leave Type</th>
                    <th>Days per Year</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="warning_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Warning Type
									</h6>
								
								</div>
			<div class="panel-body">
         
            <form class="m-b-1 add" id="warning_type_info" action="<?php echo site_url("settings/warning_type_info") ?>" name="warning_type_info" method="post">
              <div class="form-group">
                <label for="name">Warning Type</label>
                <input type="text" class="form-control" name="warning_type" placeholder="Enter Warning Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div></div>
        </div>
        
 
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
               <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Warning Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_warning_type">
                <thead>
                  <tr>
                    
                    <th>Warning Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
 
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="termination_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
         <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Termination Type
									</h6>
								
								</div>
			<div class="panel-body">
           
            <form class="m-b-1 add" id="termination_type_info" action="<?php echo site_url("settings/termination_type_info") ?>" name="termination_type_info" method="post">
              <div class="form-group">
                <label for="name">Termination Type</label>
                <input type="text" class="form-control" name="termination_type" placeholder="Enter Termination Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div>
        </div>
		</div>
        
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Termination Type
									</h6>
								
								</div>
			<div class="panel-body">
          
              <table class="table table-striped" id="xin_table_termination_type">
                <thead>
                  <tr>
                    
                    <th>Termination Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="expense_type">
								    <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
             <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Expense Type
									</h6>
								
								</div>
			<div class="panel-body">
   
            <form class="m-b-1 add" id="expense_type_info" action="<?php echo site_url("settings/expense_type_info") ?>" name="expense_type_info" method="post">
              <div class="form-group">
                <label for="name">Expense Type</label>
                <input type="text" class="form-control" name="expense_type" placeholder="Enter Expense Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form></div>
          </div>
        </div>
      
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Expense Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_expense_type">
                <thead>
                  <tr>
         
                    <th>Expense Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
 
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="job_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
            <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Job Type
									</h6>
								
								</div>
			<div class="panel-body">
      
            <form class="m-b-1 add" id="job_type_info" action="<?php echo site_url("settings/job_type_info") ?>" name="job_type_info" method="post">
              <div class="form-group">
                <label for="name">Job Type</label>
                <input type="text" class="form-control" name="job_type" placeholder="Enter Job Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form></div>
          </div>
        </div>
       
 
<?php }?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Job Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_job_type" >
                <thead>
                  <tr>
                   
                    <th>Job Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
 
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="exit_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong>  Employee Exit Type
									</h6>
								
								</div>
			<div class="panel-body">
            
            <form class="m-b-1 add" id="exit_type_info" action="<?php echo site_url("settings/exit_type_info") ?>" name="exit_type_info" method="post">
              <div class="form-group">
                <label for="name">Employee Exit Type</label>
                <input type="text" class="form-control" name="exit_type" placeholder="Enter Employee Exit Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form> </div>
          </div>
        </div>
        
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Employee Exit Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_exit_type">
                <thead>
                  <tr>
                    
                    <th>Employee Exit Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
 
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="travel_arr_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong>  Travel Arrangement Type
									</h6>
								
								</div>
			<div class="panel-body">
         
            <form class="m-b-1 add" id="travel_arr_type_info" action="<?php echo site_url("settings/travel_arr_type_info") ?>" name="travel_arr_type_info" method="post">
              <div class="form-group">
                <label for="name">Travel Arrangement Type</label>
                <input type="text" class="form-control" name="travel_arr_type" placeholder="Enter Travel Arrangement Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div>
        </div>
		 </div>
       
 
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Travel Arrangement Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_travel_arr_type">
                <thead>
                  <tr>
                   
                    <th>Travel Arrangement Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="payment_method">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Payment Method
									</h6>
								
								</div>
			<div class="panel-body">
      
            <form class="m-b-1 add" id="payment_method_info" action="<?php echo site_url("settings/payment_method_info") ?>" name="payment_method_info" method="post">
              <div class="form-group">
                <label for="name">Payment Method</label>
                <input type="text" class="form-control" name="payment_method" placeholder="Enter Payment Method">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div>
        </div>
		</div>
       
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Payment Method
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_payment_method">
                <thead>
                  <tr>
                   
                    <th>Payment Method</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
 
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="currency_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Currency
									</h6>
								
								</div>
								
			<div class="panel-body">
        
            <form class="m-b-1 add" id="currency_type_info" action="<?php echo site_url("settings/currency_type_info") ?>" name="currency_type_info" method="post">
              <div class="form-group">
                <label for="name">Currency Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Currency Name">
              </div>
              <div class="form-group">
                <label for="name">Currency Code</label>
                <input type="text" class="form-control" name="code" placeholder="Enter Currency Code">
              </div>
              <div class="form-group">
                <label for="name">Currency Symbol</label>
                <input type="text" class="form-control" name="symbol" placeholder="Enter Currency Symbol">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div>
        </div>
		</div>
       
	
	<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Currency
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_currency_type">
                <thead>
                  <tr>
                 
                    <th>Name</th>
                    <th>Code</th>
                    <th>Symbol</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="salary_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	<?php if(in_array('54a',role_resource_ids())) {?>
	  <div class="col-md-5">
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Salary Type
									</h6>
								
								</div>
			<div class="panel-body">
          
            <form class="m-b-1"  action="<?php echo site_url("settings/salary_type_info") ?>" id="salary_type_info" name="salary_type_info" method="post">
    
               <div class="form-group">
				<label for="name">Choose Parent Type</label>
                <div id="type_parent">
                  <select name="type_parent"  id="select2-demo-6" class="form-control salary_parent_type" onchange="salary_parent_type();" data-plugin="select_hrm" data-placeholder="Choose Parent Type...">
                  <option value="0">Parent</option>
                  <?php foreach($parent_type as $parent) {?>
                  <option value="<?php echo $parent->type_id;?>"> <?php echo $parent->type_name;?> </option>
                  <?php } ?>
                  </select>
                </div>
                </div>
				<div class="form-group">
                <label for="name">Salary Type</label>
                <input type="text" class="form-control" name="type_name" placeholder="Salary Type">
                </div>

				
				
					
				<div class="form-group">
				<label for="name">Adjustment Type</label>
          
                  <select name="adjustment_type" class="form-control"  data-plugin="select_hrm" data-placeholder="Choose Type...">
                  <option value="0">Internal</option>
				  <option value="1">External</option>
                
                  </select>
            
                </div>
				
				
				
				<div class="form-group type_action_area" >
                
			
            <label class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" value="1" name="action_type" checked>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">Add Action</span> </label>
            <br>

            <label class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" value="0" name="action_type">
              <span class="custom-control-indicator"></span> <span class="custom-control-description">Deduct Action</span> </label>
            <br>
                </div>



           
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form></div>
          </div>
        </div>
       
 
	
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Salary Type
									</h6>
								
								</div>
			<div class="panel-body">
      
              <table class="table table-striped" id="xin_table_salary_type">
                <thead>
                  <tr>
                    
					<th>Type Name</th>
                    <th>Parent</th>  
					<th>Adjustment Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
 
								</div>
								
<div class="tab-pane has-padding animated fadeInRight" id="tax_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Tax
									</h6>
								
								</div>
								
			<div class="panel-body">
        
            <form class="m-b-1 add" id="tax_type_info" action="<?php echo site_url("settings/tax_type_info") ?>" name="tax_type_info" method="post">
              <div class="form-group">
                <label for="name">Tax Name</label>
                <input type="text" class="form-control" name="tax_name" placeholder="Enter Tax Name">
              </div>
              <div class="form-group">
                <label for="name">Visa Type</label>
                
				
				 <select name="visa_type[]"  multiple class="form-control" data-plugin="select_hrm" data-placeholder="Select Visa Type">
            <option value=""></option>
            <?php foreach($visa_type as $visa_ty) {?>
            <option value="<?php echo $visa_ty->type_id;?>"> <?php echo $visa_ty->type_name;?></option>
            <?php } ?>
          </select>
		  
              </div>
              <div class="form-group">
                <label for="name">Tax Percentage</label>
                <input type="number" step="0.1" class="form-control" name="tax_percentage" placeholder="Enter Tax Percentage">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div>
        </div>
		</div>
       
	
	<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Tax
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_tax_type">
                <thead>
                  <tr>
                 
                    <th>Tax Name</th>
                    <th>Visa Type</th>
                    <th>Tax Percentage</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  
								</div>
								<div class="tab-pane has-padding animated fadeInRight" id="company_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
            <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> Company Type
									</h6>
								
								</div>
			<div class="panel-body">
      
            <form class="m-b-1 add" id="company_type_info" action="<?php echo site_url("settings/company_type_info") ?>" name="company_type_info" method="post">
              <div class="form-group">
                <label for="name">Company Type</label>
                <input type="text" class="form-control" name="company_type" placeholder="Enter Company Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form></div>
          </div>
        </div>
       
 
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Company Type
									</h6>
								
								</div>
			<div class="panel-body">
              <table class="table table-striped" id="xin_table_company_type" >
                <thead>
                  <tr>
                   
                    <th>Company Type</th>
					<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
 
								</div>
								

                <div class="tab-pane has-padding animated fadeInRight" id="ob_type">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	  	<?php if(in_array('54a',role_resource_ids())) {?>
        <div class="col-md-5">
           <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>Add New</strong> OB Type
									</h6>
								
								</div>
			<div class="panel-body">
  
            <form class="m-b-1 add" id="ob_type_info" action="<?php echo site_url("settings/ob_type_info") ?>" name="ob_type_info" method="post">
              <div class="form-group">
                <label for="name">OB Type</label>
                <input type="text" class="form-control" name="ob_type" placeholder="Enter OB Type">
              </div>
              <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
            </form>
          </div>
        </div>
		</div>
        
  
<?php } ?>
	
 
	 
        <div class="<?php echo $class;?>">
	
	
	
              <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  OB Type
									</h6>
								
								</div>
			        <div class="panel-body">
              <table class="table table-striped" id="xin_table_ob_type">
                <thead>
                  <tr>
                   
                    <th>OB Type</th>
					          <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  
								</div>




                <div class="tab-pane has-padding animated fadeInRight" id="country_list">
								   <div  class="box box-block bg-white">
      <div class="row">
	  
	 
        <div class="col-lg-12">	
	
              <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Country List
									</h6>
								
								</div>
			        <div class="panel-body">
              <table class="table table-striped" id="xin_table_country_list">
                <thead>
                  <tr>                  
                    <th>Country Name</th>
					          <th>Country Code</th>
                    <th>Phone Code</th>
                    <th>Lenght of Degits</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  
								</div>





								<div class="tab-pane has-padding animated fadeInRight" id="salary_field_structure">
								   <div  class="box box-block bg-white">
      <div class="row">	 
        <div class="col-lg-12">
	
	
          <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong> Salary Field Structure
									</h6>
								
								</div>
			<div class="panel-body">
      
              <table class="table table-striped" id="xin_table_salary_fields">
                <thead>
                  <tr>
                    
					<th>Country Name</th>
              		<th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
 
								</div>














<!---->
</div>
							</div>
						
						
						
						
									
								</div>
								
								
						
						</div>

					</div>
				
				
</div>




<div class="modal fade edit_setting_datail" id="edit_setting_datail" tabindex="-1" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" id="ajax_setting_info"></div>
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
