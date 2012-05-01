<?php 
	if(isset($_SESSION['username_twitter_tiwiii']))
	{
		$username_ti = $_SESSION['username_twitter_tiwiii'];
	}
	else if(isset($_SESSION['username_fb_tiwiii']))
	{
		$username_ti = $_SESSION['username_fb_tiwiii'];
	}

?>
<div id="nav">
    <ul>
        <?php if(!empty($_SESSION['tiwiii_uids8565'])){ ?>
       		<li><a id='settings' href='<?php echo URL; ?>settings' >Settings</a></li>
        <?php } else { ?>
        	<li><a id='profile'  href='#' class="remote-buttons" title='Sign into tiwiii' >Settings</a></li>
        <?php } ?>
        
        <li><a id='feed'     href='<?php echo URL; ?>activity' >Activity</a></li>                    
        <li><a id='shows'    href='<?php echo URL; ?>tv' >Shows</a></li>
        <?php if(!empty($_SESSION['tiwiii_uids8565'])){ ?>
       		 <li><a id='profile'  href='<?php echo URL; ?>user/profile/<?php echo $username_ti; ?>' >Profile</a></li>
        <?php } else { ?>
        	<li><a id='profile'  href='#' class="remote-buttons" title='Sign into tiwiii' >Profile</a></li>
        <?php } ?>
        <li><a id='home'     href='<?php echo URL;?>'>Home</a></li>      
    </ul>
</div>