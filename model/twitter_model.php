<?php

/**
 * Twitter_Model
 *
 * Model for Class Shows
 * 
 * @author Maail
 */
class Twitter_Model extends Model
{
	
	public function __construct()
	{		
				
	}
	
	public function callback()
	{
		#localhost(testing: dhisports)
		$consumer_key = 'TCP5WF9ttUa0whljQcI0MA';
		$consumer_secret = 'K1MGzxKmqnAOz3cATGnlVV5tgNgJ4UR9DmsoykGi36w';
		
		#server(tiwiii)
		/*$consumer_key = "deebX4zQrshSCPuwCibt2g";
		$consumer_secret = "oEJFTE4DJnaDxgUF4hxQqIUJjIprT8Awv4tMfkUEYs";*/

		$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
		$url = $twitterObj->getAuthenticateUrl();
	
		if(isset($_GET['oauth_token']) || (isset($_COOKIE['oauth_token']) && isset($_COOKIE['oauth_token_secret'])))
		{
			#user accepted access
			if( !isset($_COOKIE['oauth_token']) || !isset($_COOKIE['oauth_token_secret']) )
			{
				#user comes from twitter
				$twitterObj->setToken($_GET['oauth_token']);
				$token = $twitterObj->getAccessToken();
				
				#localhost
				setcookie("oauth_token", $token->oauth_token,time()+3600,"/", "", 0);
				setcookie("oauth_token_secret", $token->oauth_token_secret, time()+3600, "/", "", 0);
				
				#server
				/*$fifteen = 60 * 60 * 24 * 15 + time(); 
				setcookie("oauth_token", $token->oauth_token, $fifteen,"/", "", 0);
				setcookie("oauth_token_secret", $token->oauth_token_secret, $fifteen,"/", "", 0);*/
				
				$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
				
				$twitterInfo		                  = $twitterObj->get_accountVerify_credentials();
			    $_SESSION['id_twitter_tiwiii'] 		  = $twitterInfo->id;
			   
			    $_SESSION['image_twitter_tiwiii']     = $twitterInfo->profile_image_url;
				$_SESSION['name_twitter_tiwiii']	  = $twitterInfo->name;
				
				
				$query = new Model;
				$sql = " SELECT   userid, username as usiname, fullname as fusiname
						 FROM     users 
						 WHERE    twitid = $twitterInfo->id;";
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
					
					$new_username = $twitterInfo->screen_name;
					
					$sql = " SELECT   count(*) as check_user 
							 FROM     users 
							 WHERE    username = '$new_username'";
					if($query->query($sql))
					{
						while($row = $query->get_array())
						{
							
							extract($row);
						}
					}
					
					if($check_user >= 1)
					{
						$new_username = $new_username."2";
						
					}
					
					$data = array('twitid'  			=> $twitterInfo->id,
								  'username'  			=> $new_username,
								  'fullname'  			=> $twitterInfo->name,
								  'location'  			=> $twitterInfo->location,
								  'createddate'  		=> $createddate, 
								  'oauth_token' 		=> $token->oauth_token,
								  'oauth_token_secret'	=> $token->oauth_token_secret
								  );
			
					$query->insert_array('users',$data);
					$tiwiiiuid = mysql_insert_id();
					$_SESSION['msg'] = "You have just signed into Tiwiii. Welcome $twitterInfo->name";
					$_SESSION['tiwiii_uids8565'] = $tiwiiiuid;
					
				}
				else
				{
					$_SESSION['username_twitter_tiwiii']  = $usiname;
					$data = array('userid' => $userid,
								  'action' => "Signed in");
					$query->insert_array('userhistory',$data);
					$_SESSION['tiwiii_uids8565'] = $userid;
					$_SESSION['msg'] = "Welcome back $fusiname ";
				}
				
				//header("location:".URL);
			}
			else
			{
			 	#user switched pages and came back or got here directly, stilled logged in
				$query = new Model;
				$sql = " SELECT   userid, username as usiname 
						 FROM     users 
						 WHERE    oauth_token = ".$_COOKIE['oauth_token']."
						 AND      oauth_token_secret = ".$_COOKIE['oauth_token_secret']."";
					 
				if($query->query($sql))
				{
					while($row = $query->get_array())
					{
						$count  = $query->get_numrows();
						extract($row);
					}
				}
				
				if($count == 1){
					$twitterObj->setToken($_COOKIE['oauth_token'],$_COOKIE['oauth_token_secret']);
					
					$twitterInfo		                  = $twitterObj->get_accountVerify_credentials();
					$_SESSION['id_twitter_tiwiii'] 		  = $twitterInfo->id;
					$_SESSION['username_twitter_tiwiii']  = $usiname;
					$_SESSION['image_twitter_tiwiii']     = $twitterInfo->profile_image_url;
					$_SESSION['name_twitter_tiwiii']	  =  $twitterInfo->name;
					$_SESSION['tiwiii_uids8565'] 		  = $userid;
					
					$data = array('userid' => $userid,
								  'action' => "Signed in");
					$query->insert_array('userhistory',$data);
					$_SESSION['msg'] = "Welcome back $twitterInfo->name ";
				}
				else{
					$_SESSION['msg'] = "You could not be signed in. Please try again. ";
					
				}
				//header("location:".URL);
			}
		
			
			header("location:".URL);  
		}
		elseif(isset($_GET['denied']))
		{
			 // user denied access
			 $_SESSION['msg'] = "You must sign in through twitter first";
		}
		else
		{
			// user not logged in
			// $_SESSION['msg'] = "You are not logged in";
			header("location:$url");  
		}
		
		//header("location:".URL);
		
	}
	
	public function tweet($status){
		
		#localhost(testing: dhisports)
		$consumer_key = 'TCP5WF9ttUa0whljQcI0MA';
		$consumer_secret = 'K1MGzxKmqnAOz3cATGnlVV5tgNgJ4UR9DmsoykGi36w';
		
		#server(tiwiii)
	/*	$consumer_key = "deebX4zQrshSCPuwCibt2g";
		$consumer_secret = "oEJFTE4DJnaDxgUF4hxQqIUJjIprT8Awv4tMfkUEYs";
*/
		$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
		$twitterObj->setToken($_COOKIE['oauth_token'],$_COOKIE['oauth_token_secret']);

		$twitterObj->post('/statuses/update.json', array('status' => $status));
	
	}
}