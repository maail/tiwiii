<div id="filter-genre">
	<ul>
        <li><a href='#basic' id="basic" class='selected'>Basic Info</a></li>
        <?php
		if(isset($_SESSION['username_twitter_tiwiii']))
		{?>
		 <li><a href='#tweetupdates' id="tweetupdates">Twitter Updates</a></li>
	<?php } ?>
       
    </ul>
</div>
