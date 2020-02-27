<?php
$session = $this->session->userdata('username');
$user_info = $this->Xin_model->read_user_info($user_info_id);
// get designation
$designation = $this->Designation_model->read_designation_information($user_info[0]->designation_id);

?>



<div class="row row-md mb-1">
  <div class="col-md-6">
    <div class="box bg-white user-1">
	 <?php if($user_info[0]->profile_background!=''){
	$bg_img=$user_info[0]->profile_background;
    }else{
	 $bg_img='profile_default.jpg';
    }
  ?>
   <?php if($user_info[0]->profile_picture!='' && $user_info[0]->profile_picture!='no file') {           
            $de_file = base_url().'uploads/profile/'.$user_info[0]->profile_picture;        
			} else {?>
            <?php if($user_info[0]->gender=='Male') { ?>
            <?php $de_file = base_url().'uploads/profile/default_male.jpg';?>
            <?php } else { ?>
            <?php $de_file = base_url().'uploads/profile/default_female.jpg';?>
            <?php } ?>
            <?php } ?>
				<!-- Cover area -->
				<div class="profile-cover">
					<div class="profile-cover-img" style="background-image: url(<?php echo base_url().'uploads/profile/background/'.$bg_img;?>)"></div>
					<div class="media">
						<div class="media-left">
							<a href="#" class="profile-thumb">
								<img src="<?php echo $de_file;?>" class="img-circle" alt="">
							</a>
						</div>

						<div class="media-body">
				    		<h1><?php echo change_fletter_caps($user_info[0]->first_name.' '.$user_info[0]->middle_name.' '.$user_info[0]->last_name);?> <small class="display-block"><?php echo $designation[0]->designation_name;?></small></h1>
						</div>

       
       
                    
					</div>
				</div>
				<!-- /cover area -->
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-body" style="min-height: 27em;">
      <h2><?php echo 'Contact information';?></h2>
      <div data-pattern="priority-columns">
	    <table width="" class="table table-striped m-md-b-0">
          <tbody>
            <tr>
              <th scope="row"><?php echo $this->lang->line('dashboard_email');?></th>
              <td><?php echo $user_info[0]->email;?></td>
            </tr>			
			 <tr>
              <th scope="row"><?php echo $this->lang->line('dashboard_contact');?>#</th>
              <td><?php echo $user_info[0]->contact_no;?></td>
            </tr>	
			
            <tr>
              <th scope="row"><?php echo 'Skype';?></th>
              <td><?php echo $user_info[0]->skype_id;?></td>
            </tr>       
           
            <tr>
              <th scope="row"><?php echo $this->lang->line('dashboard_designation');?></th>
              <td><?php echo $designation[0]->designation_name;?></td>
            </tr>
            <tr>
              <th scope="row"><?php echo $this->lang->line('dashboard_dob');?></th>
			  
              <td class="blink_me">Today</td>
           

		   </tr>
          
          </tbody>
        </table>
      </div>
    </div>
  </div>
 
  </div>
  
  <div class="row row-md mb-1">
    <div class="col-md-12">
    <div class="box box-block bg-white">
      <div class="wizard" role="tabpanel">
        <ul class="nav nav-tabs m-b-1" role="tablist">
    
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#comment" role="tab"><i class="fa fa-comment"></i> Write your comment here</a> </li>
        </ul>
        <div class="tab-content m-b-1">
         
          <div role="tabpanel" class="tab-pane active fade-in" id="comment">
		  

					
            <form action="<?php echo site_url("dashboard/set_comment") ?>" method="post" name="set_comment" id="set_comment">
                   <input type="hidden" name="birthday_id" id="birthday_id" value="<?php echo $user_info_id;?>"/>
		           <input type="hidden" name="birthday_date" id="birthday_date" value="<?php echo $birday_date;?>"/>
				   <input type="hidden" name="parent" value="0"/>
			
			  <input type="hidden" name="to_id" id="to_id" value="<?php echo $user_info_id;?>"/>
			  
			  
			  <input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
			  
			  
                
                <div class="form-group">
                  <textarea name="xin_comment" id="xin_comment" class="form-control" rows="4" placeholder="Write your comment here"></textarea>
                </div>
				
				<div class="form-group">
				<div class="col-lg-12">
                <button type="submit" class="btn pull-right bg-teal-400 save">Save</button>
				</div>
              </div>
            </form>
			
			<br><br><br>
			
			
			
            <div class="clear"></div>
			
			<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">
							 
							<strong><?php echo $this->lang->line('xin_list_all');?></strong> Comments
							
							</h5>
						
						</div>
            <div>
              <table class="table table-hover mb-md-0" id="xin_comment_table" style="width:100%;">
                <thead>
                  <tr>
				    <th></th>
                    <th>All Comments</th>
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

  
  </div>
<style>
.blink_me {
  animation: blinker 1s linear infinite;
  color:red !important;
}

@keyframes blinker {  
  50% { opacity: 0; }
}

</style>
<style>
           
         
            span {
                text-transform: uppercase;
            }
            .birth_container {
        
            }
            .balloon {
                width: 738px;
                margin: 0 auto;
                padding-top: 30px;
                position: relative;
            }
            .balloon > div {
                width: 54px;
                height: 70px;
                background: rgba(182, 15, 97, 0.9);
                border-radius: 0;
                border-radius: 80% 80% 80% 80%;
                margin: 0 auto;
                position: absolute;
                padding: 10px;
                box-shadow: inset 17px 7px 10px rgba(182, 15, 97, 0.9);
                -webkit-transform-origin: bottom center;
            }
            .balloon > div:nth-child(1) {
                background: rgba(182, 15, 97, 0.9);
                left: 0;
                box-shadow: inset 10px 10px 10px rgba(135, 11, 72, 0.9);
                -webkit-animation: balloon1 6s ease-in-out infinite;
                -moz-animation: balloon1 6s ease-in-out infinite;
                -o-animation: balloon1 6s ease-in-out infinite;
                animation: balloon1 6s ease-in-out infinite;
            }
            .balloon > div:nth-child(1):before {
                color: rgba(182, 15, 97, 0.9);
            }
            .balloon > div:nth-child(2) {
                background: rgba(242, 112, 45, 0.9);
                left: 120px;
                box-shadow: inset 10px 10px 10px rgba(222, 85, 14, 0.9);
                -webkit-animation: balloon2 6s ease-in-out infinite;
                -moz-animation: balloon2 6s ease-in-out infinite;
                -o-animation: balloon2 6s ease-in-out infinite;
                animation: balloon2 6s ease-in-out infinite;
            }
            .balloon > div:nth-child(2):before {
                color: rgba(242, 112, 45, 0.9);
            }
            .balloon > div:nth-child(3) {
                background: rgba(45, 181, 167, 0.9);
                left: 240px;
                box-shadow: inset 10px 10px 10px rgba(35, 140, 129, 0.9);
                -webkit-animation: balloon4 6s ease-in-out infinite;
                -moz-animation: balloon4 6s ease-in-out infinite;
                -o-animation: balloon4 6s ease-in-out infinite;
                animation: balloon4 6s ease-in-out infinite;
            }
            .balloon > div:nth-child(3):before {
                color: rgba(45, 181, 167, 0.9);
            }
            .balloon > div:nth-child(4) {
                background: rgba(190, 61, 244, 0.9);
                left: 360px;
                box-shadow: inset 10px 10px 10px rgba(173, 14, 240, 0.9);
                -webkit-animation: balloon1 5s ease-in-out infinite;
                -moz-animation: balloon1 5s ease-in-out infinite;
                -o-animation: balloon1 5s ease-in-out infinite;
                animation: balloon1 5s ease-in-out infinite;
            }
            .balloon > div:nth-child(4):before {
                color: rgba(190, 61, 244, 0.9);
            }
            .balloon > div:nth-child(5) {
                background: rgba(180, 224, 67, 0.9);
                left: 480px;
                box-shadow: inset 10px 10px 10px rgba(158, 206, 34, 0.9);
                -webkit-animation: balloon3 5s ease-in-out infinite;
                -moz-animation: balloon3 5s ease-in-out infinite;
                -o-animation: balloon3 5s ease-in-out infinite;
                animation: balloon3 5s ease-in-out infinite;
            }
            .balloon > div:nth-child(5):before {
                color: rgba(180, 224, 67, 0.9);
            }
            .balloon > div:nth-child(6) {
                background: rgba(242, 194, 58, 0.9);
                left: 600px;
                box-shadow: inset 10px 10px 10px rgba(234, 177, 15, 0.9);
                -webkit-animation: balloon2 3s ease-in-out infinite;
                -moz-animation: balloon2 3s ease-in-out infinite;
                -o-animation: balloon2 3s ease-in-out infinite;
                animation: balloon2 3s ease-in-out infinite;
            }
            .balloon > div:nth-child(6):before {
                color: rgba(242, 194, 58, 0.9);
            }
            .balloon > div:before {
                color: rgba(182, 15, 97, 0.9);
                position: absolute;
                bottom: -11px;
                left: 20px;
                content:"▲";
                font-size: 1em;
            }
           .balloon span{
font-size: 2em;
color: white;
position: relative;
top: 7px;
left: 36%;
margin-left: -24px;            }
            /*BALLOON 1 4*/
            @-webkit-keyframes balloon1 {
                0%, 100% {
                    -webkit-transform: translateY(0) rotate(-6deg);
                }
                50% {
                    -webkit-transform: translateY(-20px) rotate(8deg);
                }
            }
            @-moz-keyframes balloon1 {
                0%, 100% {
                    -moz-transform: translateY(0) rotate(-6deg);
                }
                50% {
                    -moz-transform: translateY(-20px) rotate(8deg);
                }
            }
            @-o-keyframes balloon1 {
                0%, 100% {
                    -o-transform: translateY(0) rotate(-6deg);
                }
                50% {
                    -o-transform: translateY(-20px) rotate(8deg);
                }
            }
            @keyframes balloon1 {
                0%, 100% {
                    transform: translateY(0) rotate(-6deg);
                }
                50% {
                    transform: translateY(-20px) rotate(8deg);
                }
            }
            /* BAllOON 2 5*/
            @-webkit-keyframes balloon2 {
                0%, 100% {
                    -webkit-transform: translateY(0) rotate(6eg);
                }
                50% {
                    -webkit-transform: translateY(-30px) rotate(-8deg);
                }
            }
            @-moz-keyframes balloon2 {
                0%, 100% {
                    -moz-transform: translateY(0) rotate(6deg);
                }
                50% {
                    -moz-transform: translateY(-30px) rotate(-8deg);
                }
            }
            @-o-keyframes balloon2 {
                0%, 100% {
                    -o-transform: translateY(0) rotate(6deg);
                }
                50% {
                    -o-transform: translateY(-30px) rotate(-8deg);
                }
            }
            @keyframes balloon2 {
                0%, 100% {
                    transform: translateY(0) rotate(6deg);
                }
                50% {
                    transform: translateY(-30px) rotate(-8deg);
                }
            }
            /* BAllOON 0*/
            @-webkit-keyframes balloon3 {
                0%, 100% {
                    -webkit-transform: translate(0, -10px) rotate(6eg);
                }
                50% {
                    -webkit-transform: translate(-20px, 30px) rotate(-8deg);
                }
            }
            @-moz-keyframes balloon3 {
                0%, 100% {
                    -moz-transform: translate(0, -10px) rotate(6eg);
                }
                50% {
                    -moz-transform: translate(-20px, 30px) rotate(-8deg);
                }
            }
            @-o-keyframes balloon3 {
                0%, 100% {
                    -o-transform: translate(0, -10px) rotate(6eg);
                }
                50% {
                    -o-transform: translate(-20px, 30px) rotate(-8deg);
                }
            }
            @keyframes balloon3 {
                0%, 100% {
                    transform: translate(0, -10px) rotate(6eg);
                }
                50% {
                    transform: translate(-20px, 30px) rotate(-8deg);
                }
            }
            /* BAllOON 3*/
            @-webkit-keyframes balloon4 {
                0%, 100% {
                    -webkit-transform: translate(10px, -10px) rotate(-8eg);
                }
                50% {
                    -webkit-transform: translate(-15px, 20px) rotate(10deg);
                }
            }
            @-moz-keyframes balloon4 {
                0%, 100% {
                    -moz-transform: translate(10px, -10px) rotate(-8eg);
                }
                50% {
                    -moz-transform: translate(-15px, 10px) rotate(10deg);
                }
            }
            @-o-keyframes balloon4 {
                0%, 100% {
                    -o-transform: translate(10px, -10px) rotate(-8eg);
                }
                50% {
                    -o-transform: translate(-15px, 10px) rotate(10deg);
                }
            }
            @keyframes balloon4 {
                0%, 100% {
                    transform: translate(10px, -10px) rotate(-8eg);
                }
                50% {
                    transform: translate(-15px, 10px) rotate(10deg);
                }
            }
           
</style>
        
     
 
       
   
    
