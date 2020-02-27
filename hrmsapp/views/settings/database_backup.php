<?php
/* Database Backup Log view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1 animated fadeInRight">
  <div class="col-md-12">
     <div class="panel panel-flat">
						 		<div class="panel-heading">
									<h6 class="panel-title"><strong>List All</strong>  Backup Log
									</h6>
								<div class="heading-elements">
							    <div class="add-record-btn">
      
<form action="<?php echo site_url("settings/create_database_backup") ?>" method="post" name="db_backup" id="db_backup">
            <button type="submit" class="btn bg-teal-400  save">Create Backup</button>
          </form>

    </div>
		                	</div>
								</div>
	
        <!--<div class="delete-db-btn">
          <form action="<?php echo site_url("settings/delete_db_backup") ?>" method="post" name="del_backup" id="del_backup">
            <button type="submit" class="btn btn-primary save">Delete old backup?</button>
          </form>
        </div>
        <div class="create-db-btn">
          
        </div>-->
      
      <div  data-pattern="priority-columns">
        <table class="table table-striped" id="xin_table">
          <thead>
            <tr>              
              <th>File</th>
              <th>Date</th><th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
  </div>
</div>
