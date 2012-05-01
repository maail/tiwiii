<?php

/**
 * FB_Model
 *
 * Model for Class Shows
 * 
 * @author Maail
 */
 error_reporting(E_ALL ^ E_NOTICE); 
class FB_Model extends Model
{
	
	public function __construct()
	{		
				
	}
	
	public function callback()
	{
		#localhost(testing: echotech)
		/*$facebook = new Facebook(array(
		   'appId' => '164586206902892',
		   'secret' => '1ee5d0ec5cc7aee3a264b2d712ce578d',
		   'cookie' => true
		));*/
		
		#server(tiwiii)
		$facebook = new Facebook(array(
		   'appId' => '293237607401840',
		   'secret' => 'c76ba79e5ee9f7178cac9c63092e8180',
		   'cookie' => true
		));
				
		$user = $facebook->getUser();
		
		if($user)
		{
			   $access_token = $facebook->getAccessToken();
					
				$query = new Model;
				$sql = " SELECT   userid as uid, fullname as fname,  picture as pic, username as uname
						 FROM     users 
						 WHERE    fb_id = '$user'";
				if($query->query($sql))
				{
					while($row = $query->get_array())
					{
						$fb_check  = $query->get_numrows();
						extract($row);
					}
				}
				
				if($fb_check == 0)
				{
					//check permissions list
					$permissions_list = $facebook->api(
						'/me/permissions',
						'GET',
						array(
							'access_token' => $access_token
						)
					);
					
					//check if the permissions we need have been allowed by the user
					//if not then redirect them again to facebook's permissions page
					$permissions_needed = array('publish_stream', 'read_stream', 'offline_access');
					foreach($permissions_needed as $perm) {
						if( !isset($permissions_list['data'][0][$perm]) || $permissions_list['data'][0][$perm] != 1 ) {
							$login_url_params = array(
								'scope' => 'publish_stream,read_stream,offline_access',
								'fbconnect' =>  1,
								'display'   =>  "page",
								'next' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
							);
							$login_url = $facebook->getLoginUrl($login_url_params);
							header("Location: {$login_url}");
							exit();
						}
					}
				
					
					$fql     = 'SELECT uid, username, name, pic, hometown_location from user where uid = ' . $user;
					$fql_uinfo = $facebook->api(array(
								'method' => 'fql.query',
								'query' => $fql,
								));
					$username = $fql_uinfo[0]['username'];
					$fb_id    = $fql_uinfo[0]['uid'];
					$fullname = $fql_uinfo[0]['name'];
					$picture  = $fql_uinfo[0]['pic'];
					$location = $fql_uinfo[0]['hometown_location']['name'];
					
					
					if(empty($location)){
						$location = "";
					}
					
					if(empty($username)){
						$username = $fb_id;
					}
									
					$query = new Model;
					$sql = " SELECT   count(*) as check_user 
							 FROM     users 
							 WHERE    username = '$username'";
					if($query->query($sql))
					{
						while($row = $query->get_array())
						{
							
							extract($row);
						}
					}
					
					if($check_user >= 1)
					{
						$username = $username."2";
					}
					
					#localhost
					//setcookie("access_token", $access_token,time()+3600,"/", "", 0);
					
					#server
					$fifteen = 60 * 60 * 24 * 60 + time(); 
					setcookie("access_token", $access_token, $fifteen,"/", "", 0);
					
					$_SESSION['id_fb_tiwiii'] 	  = $fb_id;
					$_SESSION['username_fb_tiwiii'] = $username;
					$_SESSION['image_fb_tiwiii']    = $picture;
					$_SESSION['name_fb_tiwiii']     = $fullname;
					
					$query = new Model;
					$sql = " SELECT   userid
							 FROM     users
							 WHERE    username = $username;";
					if($query->query($sql))
					{
						while($row = $query->get_array())
						{
							$count  = $query->get_numrows();
							extract($row);
						}
					}
					
					$createddate = date('Y-m-d H:i:s');
					
					if($count == 0){
						$data = array('fb_id'  	     => $fb_id,
									  'username'     => $username,
									  'fullname'     => $fullname,
									  'location'     => $location,
									  'createddate'  => $createddate, 
									  'picture' 	 => $picture,
									  'access_token' => $access_token
									  );
				
						$query->insert_array('users',$data);
						$tiwiiiuid = mysql_insert_id();
						$_SESSION['msg'] = "You have just signed into Tiwiii. Welcome $fullname";
						$_SESSION['tiwiii_uids8565'] = $tiwiiiuid;
						
					}
					else
					{
						$data = array('userid' => $userid,
									  'action' => "Signed in");
						$query->insert_array('userhistory',$data);
						$_SESSION['tiwiii_uids8565'] = $userid;
						$_SESSION['msg'] = "Welcome back $fullname ";
					}
					
					header("location:".URL);  
						
					
				}
				else
				{
					#localhost
					//setcookie("access_token", $access_token,time()+3600,"/", "", 0);
					
					#server
					$fifteen = 60 * 60 * 24 * 60 + time(); 
					setcookie("access_token", $access_token, $fifteen,"/", "", 0);
										
					$_SESSION['id_fb_tiwiii'] 	  = $uid;
					$_SESSION['username_fb_tiwiii'] = $uname;
					$_SESSION['image_fb_tiwiii']    = $pic;
			    	$_SESSION['name_fb_tiwiii']     = $fname;
					
					$data = array('userid' => $uid,
								  'action' => "Signed in");
					$query->insert_array('userhistory',$data);
					$_SESSION['tiwiii_uids8565'] = $uid;
					$_SESSION['msg'] = "Welcome back $fname ";
					header("location:".URL);  
					
				}
		}
		else
		{
						
			if(isset($_POST['username_new'])){
			 	$_SESSION['username_new'] = filter_var($_POST['username_new'], FILTER_SANITIZE_STRING);
			}
			else{
				unset($_SESSION['username_new']);
			}
			
			//if not, let's redirect to the ALLOW page so we can get access
			//Create a login URL using the Facebook library's getLoginUrl() method
			$login_url_params = array(
				'scope' => 'publish_stream,read_stream,offline_access',
				'fbconnect' =>  1,
				'display'   =>  "page",
				'next' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
			);
			$login_url = $facebook->getLoginUrl($login_url_params);
			
			//redirect to the login URL on facebook
			header("Location: {$login_url}");
			exit();
		}
		
		//header("location:".URL);
		
	}
	
	/*public function update($status){
		
		
	
	}*/
}