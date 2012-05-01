<?php

/**
 * Activity_Model
 *
 * Model for Feed
 * 
 * @author Maail
 */
 error_reporting(E_ALL ^ E_NOTICE); 
class Activity_Model extends Model
{
	
	public function __construct()
	{		
				
	}
	
	public function feed($filter, $page)
	{
		$query = new Model;
		$type     = "All";
	
		
	   
		$sql = " SELECT uh.hisid
				 FROM   userhistory uh LEFT JOIN users u ON uh.userid = u.userid LEFT JOIN shows s ON uh.actionid = s.tvdb_id
				 WHERE  action NOT IN ('Signed In')
				 AND    uh.userid != 0;";
		
		if($query->query($sql))
	    {
	        while($row = $query->get_array())
	    	{
	    		$no_count = $query->get_numrows();
	    		//extract($row);		
	    	}
		}
		
		$per_page = 8; 
	    $start    = ($page-1)*$per_page;
		$cur_page = $page;
		
		$pages    = ceil($no_count/$per_page);
	    
		$next = $page + 1;
		$prev = $page - 1;	
		
		
		$sql = " SELECT timediff(now(),convert_tz(now(),@@session.time_zone,'+00:00')) as time_diff;";
		
		if($query->query($sql))
	    {
	        while($row = $query->get_array())
	    	{
	    		extract($row);		
	    	}
		}
		
		$timediff = split(':', $time_diff); 
		$hour     = $timediff[0] * 60;
		$sec      = $timediff[2] / 60;
		if($hour < 0){
			$hour_s = abs($hour);
			$mins_s = $hour_s+$sec+$timediff[1];
			$mins   = "$mins_s";
		}else{
			$mins = $hour+$sec+$timediff[1];
		}
		
				
		  	
		
		$sql = " SELECT uh.hisdate, u.twitid, u.username, u.fullname, uh.action, s.show_name, s.tvdb_id, uh.actionid, u.fb_id, u.picture
				 FROM   userhistory uh LEFT JOIN users u ON uh.userid = u.userid LEFT JOIN shows s ON uh.actionid = s.tvdb_id
				 WHERE  action NOT IN ('Signed In')
				 AND    uh.userid != 0
				 ORDER BY uh.hisid DESC
				 LIMIT  $start,$per_page";
		
		$activity_box .= "<div id='activity-box'><span id='time-diff' style='display:none;'>$mins</span>";
		
		if($query->query($sql))
	    {
	        while($row = $query->get_array())
	    	{
	    		extract($row);
				
				
				if($action == "Added show")
				{
					$action_text = "<strong>$fullname</strong> added <a href='".URL."show/view/$tvdb_id'>$show_name</a> to tiwiii";
					$action_icon = "add4";
				}
				else if($action == "Checked in")
				{
					$query2 = new Model;
					$sql = " SELECT s.show_name, s.tvdb_id as show_id, e.episode_name, e.episode_season, e.episode_number
							 FROM   episodes e LEFT JOIN shows s ON s.tvdb_id = e.tvdb_id
							 WHERE  e.tvdb_epid = $actionid";
					if($query2->query($sql))
					{
						while($row = $query2->get_array())
						{
							extract($row);		
						}
					}
					
					if($episode_number < 10)
					{$mod_no =  '0'.$episode_number;}else{$mod_no =  $episode_number;}
			
					$epno = $episode_season."".$mod_no;
					
					$action_text = "<strong>$fullname</strong> tuned into <a href='".URL."show/view/$show_id#$actionid'>$show_name - [$epno] $episode_name</a>";
					$action_icon = "checkin_m";
					
				}
				else if($action == "Watching")
				{
					$action_text = "<strong>$fullname</strong> is currently watching <a href='".URL."show/view/$tvdb_id' >$show_name</a>";
					$action_icon = "eye3";
				}
				else if($action == "Favorited")
				{
					$action_text = "<strong>$fullname</strong> favorited <a href='".URL."show/view/$tvdb_id'>$show_name</a>";
					$action_icon = "heart3";
				}
				else if($action == "Liked")
				{
					$action_text = "<strong>$fullname</strong> liked <a href='".URL."show/view/$tvdb_id'>$show_name</a>";
					$action_icon = "like_h";
				}
				else if($action == "Disliked")
				{
					$action_text = "<strong>$fullname</strong> no longer likes <a href='".URL."show/view/$tvdb_id'>$show_name</a>";
					$action_icon = "dislike_g";
				}
				else if($action == "Removed favorite")
				{
					$action_text = "<strong>$fullname</strong> removed <a href='".URL."show/view/$tvdb_id' >$show_name</a> from favorites";
					$action_icon = "heart-g";
				}
				else if($action == "Removed watching")
				{
					$action_text = "<strong>$fullname</strong> removed <a href='".URL."show/view/$tvdb_id' >$show_name</a> from currently watching";
					$action_icon = "eye-g";
				}
				
				 $hisdate_rep = str_replace('-','/',$hisdate);
				 
				 if(!empty($twitid))
				 {
					$src = "https://api.twitter.com/1/users/profile_image?user_id=".$twitid."&size=normal";
				 }
				 else
				 {
					$src = $picture;
				 }
				
				 $activity_box .= 
					"<div id='action''>
					 <span style='float:right; '><img style='border:0;' src=".URL."public/images/$action_icon.png /></span>
					 	 <a href='".URL."user/profile/$username'>
						 		<img width='48px' height='48px' src='$src' />
						  </a>
						 <p id='action-text'>$action_text</p>
					 	 <span id='his-date'><p class='days-ago'>$hisdate_rep</p></span>
						
					 </div>
					";
				$action_text = NULL;
						
	    	}
			
		
		}
		$activity_box .= "</div>";
		
		
	$activity_box .= "<ul class='pagination'>";
			 
		if($next <= $pages)$activity_box .= '<li  genre="'.$filter.'" page="'.$next.'" class="next-page">Next</li>';
		if($page <= $pages) $activity_box .= "<li class='page-info'>$page of $pages</li>";
		if($prev > 0)$activity_box .= '<li  genre="'.$filter.'" page="'.$prev.'" class="prev-page">Prev</li>';
		 
	    $activity_box .= "</ul>";
		
		echo $activity_box ;
	}
}