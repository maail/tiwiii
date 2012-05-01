<?php

/**
 * Settings_Model
 *
 * Model for Users
 * 
 * @author Maail
 */
class Settings_Model extends Model
{
	
	public function __construct()
	{		
				
	}
	
	public function view($type)
	{
		if($type == "basic"){
		$userid   = $_SESSION['tiwiii_uids8565'];
		if(!empty($userid))
		{
			$query    = new Model;
			$sql = " SELECT username as unims, fullname as funims, email FROM users WHERE userid = '$userid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);
					
					
				}
			}
			
			$msg.="<div id='show-form'>
					<h3>Basic Information</h3>
					<form method='post' id='commentForm'  action='".URL."settings/save/basic/'>
					
						<label>Username</label>
						<input type='text' name='username' value='$unims' />
						
						<label>Full Name</label>
						<input type='text' name='fullname' value='$funims' />
						
						<label>Email Address</label>
						<input type='text' name='email' value='$email' />
						
						<div style='clear:both;'></div>
						
						<input type='submit' class='button' id='save-settings' value='Save' />
				 
					</form>
				 </div>
				  <script>
				 $(document).ready(function() { 
					$('#commentForm').validate({
					
							rules: {
							fullname: 'required',
								username: {
								required: true,
								minlength: 5,
								remote: {
									url: 'http://tiwiii2.local/settings/check/user/userval' ,
									type: 'post' ,
									complete: function(data){
										
										alert(ok);
									}
								}
								
							},
							email: {
								required: true,
								email: true,
								remote: {
									url: 'http://tiwiii2.local/settings/check/email/emailval' ,
									type: 'post' ,
									complete: function(data){
										
										alert(ok);
									}
								}
								
							}
						},
						messages: {
							
							fullname: 'Please enter your fullname',
							username: {
								required: 'Please enter a username',
								minlength: 'Your username must consist of at least 5 characters',
								remote: jQuery.format('The username is not available.')
							},
							email: {
								required: 'Please enter a valid email address.',
								remote: jQuery.format('The email address has already been registered.')
							},
							
						}
						
						
					}); 
				 });
				 </script>
			";
			}
			echo $msg;
		}
		else if($type == "tweetupdates")
		{
			$userid   = $_SESSION['tiwiii_uids8565'];
			if(!empty($userid))
			{
				$query    = new Model;
				$sql = " SELECT * FROM user_options WHERE userid = '$userid'";
				if($query->query($sql))
				{
					while($row = $query->get_array())
					{
						extract($row);
						
						
					}
				}
				
				($tuned_in=='1')?$tuned_in_c='checked':$tuned_in_c='';
				($add_show=='1')?$add_show_c='checked':$add_show_c='';
				($fave_show=='1')?$fave_show_c='checked':$fave_show_c='';
				($watch_show=='1')?$watch_show_c='checked':$watch_show_c='';
				($like_show=='1')?$like_show_c='checked':$like_show_c='';
				
				
				$msg.="<div id='show-form'>
						<h3>Twitter Updates Setting</h3>
						<p style='color:#90AA67; font-weight:bold; font-size:12px;'>You can select which changes you make on Tiwiii, to be shown to your twitter followers.</p>
						<p style='color:#90AA67; font-weight:bold; font-size:12px;'>We won't make any twitter updates without your permission, we promise.</p>
						<form method='post' id='commentForm'  action='".URL."settings/save/twitter/'>
						
						<span class='cbox-label' style='margin:10px 0 10px 0;'>
          	        	     <input class='checkbox' type='checkbox'  name='select_all' id='select_all' /> All Updates
          	        	</span>
						
						<div style='margin:0 0 0 10px;'>
							<span class='cbox-label'>
								 <input class='checkbox' type='checkbox'  name='tuned_in' $tuned_in_c  /> Tune into TV Shows
							</span>
							
							<span class='cbox-label'>
								 <input class='checkbox' type='checkbox'  name='add_show'  $add_show_c /> Add show to Tiwiii
							</span>
							
							<span class='cbox-label'>
								 <input class='checkbox' type='checkbox'  name='fave_show' $fave_show_c /> Add to Favorites / Remove from Favorites
							</span>
							
							<span class='cbox-label'>
								 <input class='checkbox' type='checkbox'  name='watch_show' $watch_show_c  /> Add to Currently Watching / Remove from Currently Watching
							</span>
							
							<span class='cbox-label'>
								 <input class='checkbox' type='checkbox'  name='like_show' $like_show_c  /> Like a show / Dislike a show
							</span>
						</div>
						
							
						<input type='submit' class='button' id='save-settings' value='Save' />
					 
						</form>
					 </div>
					 <script>
					 $('#select_all').change(function() {
							var checkboxes = $(this).closest('form').find(':checkbox');
							if($(this).is(':checked')) {
								checkboxes.attr('checked', 'checked');
							} else {
								checkboxes.removeAttr('checked');
							}
						});
					 
					 
					 </script>
				";
			 }
			 echo $msg;
		}
	}
	
	public function check($type, $typeval)
	{
		$userid   = $_SESSION['tiwiii_uids8565'];
		if($type == "user" && !empty($userid))
		{
			if(isset($_POST["username"])){
				$username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
			}
			else{
				$username = $typeval;
			}
			$query    = new Model;
			
			
			if(preg_match("/^[a-zA-Z0-9]+$/", $username))
			{
				
				$sql = " SELECT username as old_user FROM users WHERE userid = '$userid'";
				if($query->query($sql))
				{
					while($row = $query->get_array())
					{
						extract($row);
						
						
					}
				}
				
				if($old_user != $username){
					$sql = " SELECT count(*) as check_user FROM users WHERE username = '$username'";
					if($query->query($sql))
					{
						while($row = $query->get_array())
						{
							extract($row);
						}
					}
				
					
					if($check_user == 0)
					{
						$valid = "true";
					}
					else
					{
						$valid = "false";
					}
						
				}
				else
				{
					  $valid = "true";
				}
			
			}
			else
			{
				$valid = "false";
			}
			
			if( $typeval == "userval"){
				echo $valid;
			}
			else
			{
				return $valid;
			}
		
		}else if($type == "newuser")
		{
			if(isset($_POST["username_new"])){
				$username = filter_var($_POST["username_new"], FILTER_SANITIZE_STRING);
			}
			else{
				$username = $typeval;
			}
			$query    = new Model;
			
			
			if(preg_match("/^[a-zA-Z0-9]+$/", $username))
			{
				  $sql = " SELECT count(*) as check_user FROM users WHERE username = '$username'";
				  if($query->query($sql))
				  {
					  while($row = $query->get_array())
					  {
						  extract($row);
					  }
				  }
			  
				  if($check_user == 0)
				  {
					  $valid = "true";
				  }
				  else
				  {
					  $valid = "false";
				  }
			}
			else
			{
				$valid = "false";
			}
			
			echo $valid;
			
		
		}
		else if($type == "email" && !empty($userid))
		{
			if(isset($_POST["email"])){
				$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
			}
			else{
				$email = $typeval;
			}
			
			$query    = new Model;
			
			if(preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", $email))
			{
				
				$sql = " SELECT email as old_email FROM users WHERE userid = '$userid'";
				if($query->query($sql))
				{
					while($row = $query->get_array())
					{
						extract($row);
						
						
					}
				}
				
				if($old_email != $email){
					$sql = " SELECT count(*) as check_user FROM users WHERE email = '$email'";
					if($query->query($sql))
					{
						while($row = $query->get_array())
						{
							extract($row);
						}
					}
				
					
					if($check_user == 0)
					{
						$valid = "true";
					}
					else
					{
						$valid = "false";
					}
						
				}
				else
				{
					  $valid = "true";
				}
			
			}
			else
			{
				$valid = "false";
			}
			if( $typeval == "emailval"){
				echo $valid;
			}
			else
			{
				return $valid;
			}
		
		}
		
		
	}
	
	public function save($type)
	{
		$userid  = $_SESSION['tiwiii_uids8565'];
		if($type == "basic" && !empty($userid))
		{
			$query = new Model;
			
			$username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
			$fullname = filter_var($_POST["fullname"], FILTER_SANITIZE_STRING);
			$email    = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
			
			$settings    =  new Settings_Model;
			$check_user  = $settings->check("user",$username);
			$check_email = $settings->check("email",$email);
			
			if($check_user == "true" && $check_email == "true" )
			{
				$data = array('username' => $username,
							  'fullname' => $fullname,
							  'email'    => $email
							  );
							  
				$query->update_array('users',$data, "userid = '$userid'");
				$_SESSION['msg'] = "Your basic information has been updated";
				
				if(isset($_SESSION['username_twitter_tiwiii']))
				{
					$_SESSION['username_twitter_tiwiii'] = $username;
				}
				else if(isset($_SESSION['username_fb_tiwiii']))
				{
					$_SESSION['username_fb_tiwiii'] = $username;
				}
				
				
			}
			else{
				$_SESSION['msg'] = "Your basic information could not be updated";
			}
			
			header("location:".URL."settings");
		}
		else if($type == "twitter" && !empty($userid))
		{
			$query = new Model;
						
			(isset($_POST['tuned_in']))?$tuned_in ='1':$tuned_in ='0';
			(isset($_POST['add_show']))?$add_show ='1':$add_show ='0';
			(isset($_POST['fave_show']))?$fave_show ='1':$fave_show ='0';
			(isset($_POST['watch_show']))?$watch_show ='1':$watch_show ='0';
			(isset($_POST['like_show']))?$like_show ='1':$like_show ='0';
			
			$data = array('userid'   => $userid,
						  'tuned_in'   => $tuned_in,
						  'add_show'   => $add_show,
						  'fave_show'  => $fave_show,
						  'watch_show' => $watch_show,
						  'like_show'  => $like_show
						  );
						  
			
			$sql = " SELECT count(*) as check_user FROM user_options WHERE userid = '$userid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);
				}
			}
			
			if($check_user == 0){
				$query->insert_array('user_options',$data);
			}
			else
			{
				$query->update_array('user_options',$data, "userid = '$userid'");
			}
			
			$_SESSION['msg'] = "Your twitter update settings has been updated";
			
			
			header("location:".URL."settings#tweetupdates");
		}
		
	}
}