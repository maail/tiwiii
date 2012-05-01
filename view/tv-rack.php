<div id="tv-rack">
	<div id="search">
		<form method="post" action="">
	  		<input type="text" id="q" name="q" maxlength="30" />
		</form>
	</div>
    
    <?php
	#localhost(testing: dhisports)
	/*$consumer_key = 'TCP5WF9ttUa0whljQcI0MA';
    $consumer_secret = 'K1MGzxKmqnAOz3cATGnlVV5tgNgJ4UR9DmsoykGi36w';*/
		
	#server(tiwiii)
	$consumer_key = "deebX4zQrshSCPuwCibt2g";
	$consumer_secret = "oEJFTE4DJnaDxgUF4hxQqIUJjIprT8Awv4tMfkUEYs";
	
	
	$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);	

		
	if(isset($_GET['oauth_token']) || (isset($_COOKIE['oauth_token']) && isset($_COOKIE['oauth_token_secret']) ))
	{
		if((!isset($_SESSION['username_twitter_tiwiii'])) && (!isset($_SESSION['image_twitter_tiwiii'])) && (!isset($_SESSION['userid_twitter_tiwiii'])))
		{
			$query = new queryDB;
			
			$sql = " SELECT   userid 
					 FROM     users 
					 WHERE    oauth_token = '".$_COOKIE['oauth_token']."'
					 AND      oauth_token_secret = '".$_COOKIE['oauth_token_secret']."'";
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
				$_SESSION['username_twitter_tiwiii']  = $twitterInfo->screen_name;
				$_SESSION['image_twitter_tiwiii']     = $twitterInfo->profile_image_url;
				$_SESSION['name_twitter_tiwiii']	  = $twitterInfo->name;
				$_SESSION['tiwiii_uids8565'] 		  = $userid;
				
				$data = array('userid' => $userid,
							  'action' => "Signed in");
				$query->insert_array('userhistory',$data);
			}
			
		}		
		
		$user_image    = $_SESSION['image_twitter_tiwiii']; 
		$userids       = $_SESSION['tiwiii_uids8565'];
		$profile_image =  "<img src='$user_image' alt='".$_SESSION['username_twitter_tiwiii']."'></img>";
		$username      = $_SESSION['username_twitter_tiwiii'];
		
		$query = new queryDB;
		$sql = " SELECT   count(*) as count_fave
				 FROM     user_fave
				 WHERE    userid = $userids";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
			}
		}
		
		$sql = " SELECT   count(*) as count_watch
				 FROM     user_watch
				 WHERE    userid = $userids";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
			}
		}
		
		$watching = "<li id='rack-watch' >
					 	<span id='watch-no'>$count_watch</span>
						<a alt='Currently Watching' class='remote-buttons' title='Your watching' id='eye-show' href='#' style='margin:0px 3px 0 0;' >Watching</a>
					</li>";
		$fave     = "<li id='rack-fave'>
						<span id='fave-no'>$count_fave</span>
						<a alt='Favorite' class='remote-buttons' title='Your favorites' id='heart-show' href='#' style='margin:-1px 5px 0 0;' >Fave</a>
					</li>";
		
		?>
        <div id="user-info">
        
        	<div id="user-image"><?php echo "<a href='".URL."user/profile/$username' title='$funim'>$profile_image</a>"; ?></div>
             
            <div id="user-names">
                <span id="user-full-name">
					<?php echo $_SESSION['name_twitter_tiwiii']; ?>
                 </span>
                 <a id = "logout" title="Sign Out" class='remote-buttons' href="<?php echo URL;?>user/logout">Sign Out</a>
                <span id="user-name"><a href = "<?php echo URL;?>user/profile/<?php echo $username; ?>">@<?php echo $username; ?></a> </span>
                <?php echo $fave; echo $watching;  ?>
               
        	</div>
        
        </div>
  <?php      
	}
	else if((isset($_COOKIE['access_token'])))
	{
		if((!isset($_SESSION['username_fb_tiwiii'])) && (!isset($_SESSION['image_fb_tiwiii'])))
		{
			$query = new queryDB;
			
			$sql = " SELECT   userid as uids, username as unims, picture as pics, fb_id, fullname as funims
					 FROM     users 
					 WHERE    access_token = '".$_COOKIE['access_token']."'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					$count  = $query->get_numrows();
					extract($row);
				}
			}
			
			if($count == 1){
				
				$_SESSION['username_fb_tiwiii']  = $unims;
				$_SESSION['image_fb_tiwiii']     = $pics;
				$_SESSION['name_fb_tiwiii']	  = $funims;
				$_SESSION['tiwiii_uids8565'] 		  = $uids;
				
				$data = array('userid' => $uids,
							  'action' => "Signed in");
				$query->insert_array('userhistory',$data);
			}
			
		}
		
		$user_image    = $_SESSION['image_fb_tiwiii']; 
		$userids       = $_SESSION['tiwiii_uids8565'];
		$profile_image =  "<img width='48px' height='48px' src='$user_image' alt='".$_SESSION['image_fb_tiwiii']."'></img>";
		$username      = $_SESSION['username_fb_tiwiii'];
		
		$query = new queryDB;
		$sql = " SELECT   count(*) as count_fave
				 FROM     user_fave
				 WHERE    userid = $userids";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
			}
		}
		
		$sql = " SELECT   count(*) as count_watch
				 FROM     user_watch
				 WHERE    userid = $userids";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
			}
		}
		
		$watching = "<li id='rack-watch' >
					 	<span id='watch-no'>$count_watch</span>
						<a alt='Currently Watching' class='remote-buttons' title='Your watching' id='eye-show' href='#' style='margin:0px 3px 0 0;' >Watching</a>
					</li>";
		$fave     = "<li id='rack-fave'>
						<span id='fave-no'>$count_fave</span>
						<a alt='Favorite' class='remote-buttons' title='Your favorites' id='heart-show' href='#' style='margin:-1px 5px 0 0;' >Fave</a>
					</li>";
		
		?>
        <div id="user-info">
        
        	<div id="user-image"><?php echo "<a href='".URL."user/profile/$username' title='$funim'>$profile_image</a>"; ?></div>
             
            <div id="user-names">
                <span id="user-full-name">
					<?php echo $_SESSION['name_fb_tiwiii']; ?>
                 </span>
                 <a id = "logout" title="Sign Out" class='remote-buttons' href="<?php echo URL;?>user/logout">Sign Out</a>
                <span id="user-name"><a href = "<?php echo URL;?>user/profile/<?php echo $username; ?>">@<?php echo $username; ?></a> </span>
                <?php echo $fave; echo $watching;  ?>
               
        	</div>
        
        </div>
	<?php	
	}
	else
	{ 
		session_unset();
		session_destroy();
		$url = "".URL."twitter/callback";
		$url_facebook = "".URL."fb/callback";
		echo "<a href=".$url." id='sign_in'>Sign in with twitter</a>";
		echo "<a href=".$url_facebook." id='sign_in_f'>Sign in with facebook</a>";
 	}
	?>
    
    <div id="users">
    <span>Current Users</span>
    <div style="clear:both;"></div>
    	<div id="users-page">
   
   	 </div>
    </div> 
    
    <div style="clear:both;"></div>
     <div style="float:left;margin:40px 0 0 6px;">
    	<div class="fb-like" data-href="http://www.facebook.com/tiwiiiapp" data-send="false" data-layout="button_count" data-width="40" data-show-faces="false" style="float: left;
width: 78px;"></div>
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://tiwiii.com" data-text="Do not miss your favorite shows ever again. Sign into Tiwiii and keep track of your fave shows while sharing them with your friends." data-via="tiwiiiapp" data-related="tiwiiiapp" data-hashtags="tiwiii">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    </div>
    
    <div style="clear:both;"></div>
    <div style="float: right; margin: 20px 34px 0 5px;">
    	<a href="http://twitter.com/tiwiiiapp" id="twit_info" class='remote-buttons' title="Follow us on twitter" target="_blank">Twitter</a>
        <a href="http://www.facebook.com/pages/Tiwiii/297368720320320" class='remote-buttons' id="fb_info" title="Like us on Facebook" target="_blank">Facebook</a>
    </div>
  
</div>