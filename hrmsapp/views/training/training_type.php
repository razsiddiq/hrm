<?php
/* Training Type view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1 animated fadeInRight">
  <div class="col-md-4">
       <div class="panel panel-flat">
   
	  <div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_add_new');?></strong> Type
							
								</h5>
					
						</div>
     <div class="panel-body">
      <form class="m-b-1 add" method="post" action="<?php echo site_url("training_type/add_type") ?>" name="add_type" id="xin-form">
        <div class="form-group">
          <label for="type_name">Training Type</label>
          <input type="text" class="form-control" name="type_name" placeholder="Enter Training Type">
        </div>
        <button type="submit" class="btn bg-teal-400 pull-right save">Save</button>
      </form>
     </div>
   </div>
  </div>
  <div class="col-md-8">
      <div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Training Types
							
							</h5>
						
						</div>

      <div  data-pattern="priority-columns">
        <table class="table table-striped " id="xin_table" >
          <thead>
            <tr>             
              <th>ID</th>
              <th>NAME</th>
			  <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
  </div>
</div>
