<?php
/**
 * Show_Model
 *
 * Model for Class Shows
 * 
 * @author Maail
 */
error_reporting(E_ALL ^ E_NOTICE); 
class Show_Model extends Model
{

	public function __construct()
	{		
				
	}
	
	public function view($id)
	{		
		global $accordian;
		$s_id = $id;
		
		$query = new Model();
		//$sql = " SELECT * from shows WHERE tvdb_id = $s_id";
		
		$sql = " SELECT   s.*, count(uf.tvdb_id) as count_uf, UW.count_uw
				 FROM     shows s LEFT JOIN user_fave uf ON s.tvdb_id = uf.tvdb_id,
						   (SELECT count(uw.tvdb_id) as count_uw, s.tvdb_id
							FROM   shows s LEFT JOIN user_watch uw ON s.tvdb_id = uw.tvdb_id
							WHERE  s.tvdb_id = $s_id) AS UW
				 WHERE    s.tvdb_id = $s_id
				 GROUP BY s.tvdb_id";
		
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
				$basic_info = $row;
				
			}
		}
		
		
		if($show_status == 'Continuing'){
		$sql = " SELECT   * 
				 FROM     episodes 
				 WHERE    (firstaired = CURDATE() OR firstaired >= NOW())
				 AND      tvdb_id = '$s_id'
				 AND episode_season !=0
				 ORDER BY firstaired ASC
				 LIMIT 1";
		}
		else{
		$sql = " SELECT   * 
				 FROM     episodes 
				 WHERE    tvdb_id = '$s_id'
				 AND episode_season !=0
				 ORDER BY firstaired DESC, tvdb_epid DESC
				 LIMIT 1;";
		}
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				$count_show = $query->get_numrows();
				extract($row);
				$episode_info = $row;
			}
		}
		$count_show2 = 1;
		if(empty($count_show) || $count_show == 0)
		{
			$sql = " SELECT   * 
					 FROM     episodes 
					 WHERE    tvdb_id = '$s_id'
					 AND episode_season !=0
					 ORDER BY firstaired DESC, tvdb_epid DESC
					 LIMIT 1";
					 
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					$count_show2  = $query->get_numrows();
					$episode_info = $row;
					//extract($row);
				}
			}
		
		}
		
		if(!empty($episode_info)){
			$show_info = array_merge($basic_info, $episode_info);
		}
		else{$show_info = $basic_info; }
		
		$votes= new Show_Model;
		$no_votes = $votes->count_vote($s_id);
		
		$show_info['count_uv'] = $no_votes;	
		$show_info['count_show2'] = $count_show2;
		$show_info['action'] = "show_info";
		
		$img     = 'public/uploads/series/'.$s_id.'.jpg';
	    if (file_exists($img)) {
			$the_selected_poster = " <a href='".URL."show/view/$s_id'><img src = '".URL."public/image.php/$s_id.jpg?width=220&amp;image=".URL."public/uploads/series/$s_id.jpg' alt='$s_id'> </a>";
		}
		
		$show_info['the_selected_poster'] = $the_selected_poster;

		$sql          = " SELECT episode_season FROM episodes WHERE  tvdb_id = $s_id AND episode_season !=0 GROUP BY episode_season";
		$seasons = array();
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
				$seasons[] = $episode_season;
			}
		}
		
		$tree         = NULL;
		$i 			  = NULL;
		$tempTree     = NULL;
		foreach ($seasons as $key => $value)
		{
			$i = $value;
		    $sql = " SELECT tvdb_epid,episode_name,episode_number FROM episodes WHERE episode_season=$value AND tvdb_id = $s_id ORDER BY episode_number ASC";			 
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);
					
					$ep_no = $episode_number;
					
					if($ep_no < 10)
					{$mod_no =  $value.'0';}else{$mod_no =  $value;}
						
					if(isset($_SESSION['tiwiii_uids8565'])){
						$check_in = "<span id='check-in'  class='check-in' alt='#$tvdb_epid'>Check In</span>";
					}
					else{
						$check_in = "<span id='check-in' alt='Sign into Tiwiii'  class='checkin-buttons'>Check In</span>";
					}
						
							  
					$tempTree[$i] .= "<li style='float:left;'>" ."
					<a style=' float:left; width:13.4em; '  href='#".($tvdb_epid)."'  class='ep-number'>
						<span id='checkin-ep'>
						$check_in
					".  $mod_no . "".  $episode_number . " - ".  $episode_name ."</span></a>" . "
					
					</li>\n";
				}
			}
			
			$tree .= "<li style='display:block;'>"."<a href='#'>Season ". $value ."</a> \n" ;
			$tree .= "<ul class='acitem'>\n";
			$tree .= $tempTree[$i];
			$tree .= "</ul>\n";
			$tree .= "</li>\n";
			
		}
		
		$accordian .=  "<ul class='menu collapsible'>";
		$accordian .=  $tree;
		$accordian .=  "</ul>"; 
		
		$show_info['accordian'] = $accordian;
		return $show_info;  	
	}
	
	public function activity($show, $page)
	{
		$query = new Model;
			
		$sql = " SELECT uh.hisid
				 FROM   userhistory uh LEFT JOIN users u ON uh.userid = u.userid LEFT JOIN shows s ON uh.actionid = s.tvdb_id
				 WHERE  action NOT IN ('Signed In')
				 AND    tvdb_id = '$show'
				 AND    uh.userid != 0;";
		
		if($query->query($sql))
	    {
	        while($row = $query->get_array())
	    	{
	    		$no_count = $query->get_numrows();
	    		//extract($row);		
	    	}
		}
		
		$per_page = 3; 
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
				 AND    tvdb_id = '$show'
				 ORDER BY uh.hisid DESC
				 LIMIT  $start,$per_page";
		
		$activity_box .= "<p style='margin: 10px 0 -5px 20px; font-size: 16px; font-weight: bold; color: #A9ACB1;'>Activity</p>
		<div id='activity-box' style='margin-top:0; '><span id='time-diff' style='display:none;'>$mins</span>";
		
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
					"<div id='action' style='margin-left:5px;'>
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
			 
		if($next <= $pages)$activity_box .= '<li  show="'.$show.'" page="'.$next.'" class="next-page">Next</li>';
		if($page <= $pages) $activity_box .= "<li class='page-info'>$page of $pages</li>";
		if($prev > 0)$activity_box .= '<li  show="'.$show.'" page="'.$prev.'" class="prev-page">Prev</li>';
		 
	    $activity_box .= "</ul>";
		
		echo $activity_box ;
	}
	
	public function episode($epid){
		
		$epid = str_replace('#','',$epid);
	
		$query = new Model();
		$sql = " SELECT *  from episodes WHERE tvdb_epid = $epid ";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
			}
		}
		if($firstaired == "1970-01-01" || $firstaired == "1969-12-31" ){$firstaired = "TBA";}	
		
		if($firstaired > date('Y-m-d',strtotime("Now"))){ $airs = "Airing On : ";} else { $airs = "Aired On : ";}
		
		if(!empty($firstaired)){$firstaired_p = "<p><strong>$airs</strong>$firstaired</p>";} else{$firstaired_p=NULL;}
		if(!empty($gueststars)){$gueststars_p = "<p><strong>Guest Stars : </strong>$gueststars</p>";} else{$gueststars_p=NULL;}
		if(!empty($directors)){$directors_p  = "<p><strong>Directors : </strong>$directors</p>";} else{$directors_p=NULL;}
		if(!empty($writers)){$writers_p = "<p><strong>Writers : </strong>$writers</p>";}else{$writers_p=NULL;} 
		
		if($episode_number < 10)
				{$mod_no =  '0'.$episode_number;}else{$mod_no =  $episode_number;}
				
		$episode_info = NULL;
		$today = date('Y-m-d');
		
		if($firstaired > $today)
		{
			$episode_info = "Upcoming Episode";
		}
		else if($firstaired < $today)
		{
			$episode_info = "Previous Episode";	
		}
		else if($firstaired == $today)
		{
			$episode_info = "Airing Today";	
		}
		
		$ep_info = "
		<div id='the-show-ep' style='margin:40px 0 0 10px;'>
			<span id='episode-info'>$episode_info</span>
			<div class='shows'>
				<div id='show-info' style='width:98%;'>
					<span id='ep-name' style='display:none;'>$episode_name</span>
					<h3>$episode_season$mod_no - $episode_name</h3>
					<p>$episode_overview</p>
					$firstaired_p
					$gueststars_p
					$directors_p
					$writers_p
				</div>
			</div>	
		</div>";
		
		echo $ep_info;
	
	}
	
	public function update($showid)
	{
		$query = new Model;
		
		$sql = " SELECT show_name from shows WHERE tvdb_id = $showid ";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
				
			}
		}
		$episodes         = TV_Shows::search_all($showid);
		foreach($episodes as $episode){
			$tvdb_epid        = $episode->id;
			$episode_season   = $episode->season;
			$episode_number   = $episode->number;
			$episode_name     = $episode->name;
			$firstaired       = date('Y-m-d',$episode->firstAired);
			$gueststars       = implode(', ',$episode->guestStars);
			$directors        = implode(', ',$episode->directors);
			$writers          = implode(', ',$episode->writers);
			$episode_overview = $episode->overview;
			$imdb_id          = $episode->imdbId;
			
			$sql = " SELECT count(*) as count from episodes WHERE tvdb_epid = $tvdb_epid ";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);
					
				}
			}
			
			$data = array('tvdb_epid'  		=> $tvdb_epid,
						  'episode_season'  => $episode_season,
						  'episode_number'  => $episode_number, 
						  'episode_name'	=> $episode_name, 
						  'firstaired' 		=> $firstaired,
						  'gueststars'		=> $gueststars,
						  'directors' 		=> $directors,
						  'writers' 		=> $writers,
						  'episode_overview'=> $episode_overview,
						  'imdb_id' 		=> $imdb_id,
						  'tvdb_id' 		=> $showid
						  );
		
						  
		   if($count == 0){
				$query->insert_array('episodes',$data);
		   }
		   else
		   {
				$query->update_array('episodes',$data, "tvdb_epid = '$tvdb_epid'");
		   }
		
		}
		
		$show         = TV_Shows::findById($showid);
		$show_aired   = date('Y-m-d',$show->firstAired);
		$show_network = $show->network;
		$show_status  = $show->status;
		$show_airtime = date('H:i:s',strtotime($show->airTime));
		$show_airday  = $show->dayOfWeek;
					
		$date   = date('Y:m:d H:i:s');		
		$data2  = array( 'show_aired'  => $show_aired, 
						  'show_network'=> $show_network, 
						  'show_status' => $show_status,
						  'show_airtime'=> $show_airtime,
						  'show_airday' => $show_airday,
						  'update_date' => $date, 
						  'ep_status' => 'Active'
						  );
		$query->update_array('shows',$data2, "tvdb_id = '$showid'");
		$_SESSION['msg'] = "The show $show_name has been updated";
		
	}
	
	public function update_by($type)
	{
		set_time_limit (0);
		
		$query    = new Model;
		$episodes = TV_Shows::update_episodes($type);
		
		foreach($episodes as $epi){
			
			$s_id  = $epi->Series;
			$ep_id = $epi->id;
			
			$sql = " SELECT count(*) as count1 from shows WHERE tvdb_id = $s_id AND ep_status != 'Locked' ";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);
					if($count1>0){
						
											
						$episode = TV_Shows::episode_info($ep_id);
						
						$tvdb_epid        = $episode->id;
						$episode_season   = $episode->season;
						$episode_number   = $episode->number;
						$episode_name     = $episode->name;
						$firstaired       = date('Y-m-d',$episode->firstAired);
						$gueststars       = implode(', ',$episode->guestStars);
						$directors        = implode(', ',$episode->directors);
						$writers          = implode(', ',$episode->writers);
						$episode_overview = $episode->overview;
						$imdb_id          = $episode->imdbId;
						
						$query2  = new Model;
						$sql     = " SELECT count(*) as count2 from episodes WHERE tvdb_epid = $tvdb_epid ";
						if($query2->query($sql))
						{
							while($row = $query2->get_array())
							{
								extract($row);
								
							}
						}
						
						$data = array('tvdb_epid'  		=> $tvdb_epid,
									  'episode_season'  => $episode_season,
									  'episode_number'  => $episode_number, 
									  'episode_name'	=> $episode_name, 
									  'firstaired' 		=> $firstaired,
									  'gueststars'		=> $gueststars,
									  'directors' 		=> $directors,
									  'writers' 		=> $writers,
									  'episode_overview'=> $episode_overview,
									  'imdb_id' 		=> $imdb_id,
									  'tvdb_id' 		=> $s_id
									  );
					   if($count2 == 0){
						    
							$query2->insert_array('episodes',$data);
					   }
					   else
					   {
							$query2->update_array('episodes',$data, "tvdb_epid = '$tvdb_epid'");
					   }
					   
					    $sql     = " SELECT count(*) as count3 from episodes WHERE episode_season = $episode_season and episode_number=$episode_number and tvdb_id = $s_id ";
						if($query2->query($sql))
						{
							while($row = $query2->get_array())
							{
								extract($row);
								
							}
						}
					  
					   if($count3 > 1){
						   
						   $sql    = "DELETE FROM episodes WHERE episode_season = $episode_season and episode_number=$episode_number and tvdb_id = $s_id and  tvdb_epid != $tvdb_epid;";
						   $result = mysql_query($sql);
						   
					   }
					}
				}
			}
		}
		
		$series = TV_Shows::update_series($type);
		
		foreach($series as $seri){
			
			$s_id  = $seri->id;
			
			 $sql = " SELECT count(*) as count4 from shows WHERE tvdb_id = $s_id  AND ep_status != 'Locked'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);
					if($count4>0){
						
						//echo $s_id;
						//echo "</br>";
						$show         = TV_Shows::findById($s_id);
						
						$show_name    = $show->seriesName;
						if(!empty($show->genres)){$show_genre   = implode(', ',$show->genres);}
						$show_desc    = $show->overview;
						$show_aired   = date('Y-m-d',$show->firstAired);
						$show_network = $show->network;
						$show_status  = $show->status;
						$show_runtime = $show->runtime;
						$imdb_id      = $show->imdbId;						
						$show_airtime = date('H:i:s',strtotime($show->airTime));
						$show_airday  = $show->dayOfWeek;
						
						if($show_network == 'Revision3'){
							$show_genre   = "Podcasts";
						}
						
						$query2 = new Model;
						$date  = date('Y:m:d H:i:s');
						$data  = array('show_genre'  => $show_genre,
									  'show_name'   => $show_name,
									  'show_desc'   => $show_desc,
									  'show_aired'  => $show_aired, 
									  'show_network'=> $show_network, 
									  'show_status' => $show_status,
									  'show_runtime'=> $show_runtime,
									  'imdb_id    ' => $imdb_id,
									  'show_airtime'=> $show_airtime,
									  'show_airday' => $show_airday,
									  'update_date' => $date
									  );
						$query2->update_array('shows',$data, "tvdb_id = '$s_id'");
						
						
					}
				}
			}
		}
	}
	
	public function search(){
		$show_info = array();
		$show_info['action'] = "add_blank";
		return $show_info;
	}
	
	public function update_misc(){
		
		/*$query = new Model;
		$query2 = new Model;
		
		$sql = " SELECT tvdb_id, show_airtime from shows";
							 
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);	
				
				$show_airtime_new = date('H:i:s', strtotime($show_airtime));
				$data = array('show_airtime' => $show_airtime_new);
				$query2->update_array('shows',$data, "tvdb_id = '$tvdb_id'");
			}
		}*/
	}
	
	public function query(){
		
		$thequery = filter_var($_POST['s'], FILTER_SANITIZE_STRING);
		
		if(!empty($thequery))
		{
			$result = "<div id='content-div'>";
			$result = "<h3 style='margin:10px 0 -5px 0;'>Search Results</h3>";
			
			$query = new Model;
			$tvshow = TV_Shows::search($thequery);
			$no_of = count($tvshow);
			
			if(!empty($tvshow)){
				$result .="<ul>";
				foreach($tvshow as $show)
				{
					$show_no      = $show->id;
					$show_name    = $show->seriesName;
					
					$sql = " SELECT count(*) as no_count
							 FROM   shows
							 WHERE  tvdb_id = '$show_no'";
							 
					if($query->query($sql))
					{
						while($row = $query->get_array())
						{
							extract($row);		
						}
					}
					if($no_count > 0)
					{
						$result .= "<li><a href='".URL."show/view/$show_no' style='color:#246CA4;'>$show_name </a>(Already on Tiwiii)</li>";
					}
					else
					{
						$result .= " <li><a href='".URL."show/add/$show_no'>$show_name</a></li>";
					}
				}
				$result .= "</ul>";
				$result .= "</div>";
			}
			else
			{
				$result = "<span style ='color: #7B7981; margin: 15px 0 0; display:block;'>There seems to be a problem contacting the interwebs. Please check again later.</span>";
			}
			echo $result;
		}
	}
	
	public function add($showid){
		$show_info = array();
		if(check_int($showid) === TRUE )
		{
			$tvdbid = $showid;
		}
		else
		{
			header('location:404.php');
		}
		
		$query = new Model;
		$sql = " SELECT count(*) as no_count, show_name as s_name
				 FROM   shows
				 WHERE  tvdb_id = '$tvdbid'";
				 
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);		
			}
		}
		
		if($no_count > 0)
		{
			$show_info['result'] = "The show <a href='".URL."show/view/$showid'> $s_name </a>already exists on tiwiii</br>";
		}
		else
		{
			$show         = TV_Shows::findById($tvdbid);
			$show_name    = $show->seriesName;
			if(!empty($show->genres)){$show_genre   = implode(', ',$show->genres);}
			$show_desc    = $show->overview;
			$show_aired   = date('Y-m-d',$show->firstAired);
			$show_network = $show->network;
			$show_status  = $show->status;
			$show_runtime = $show->runtime;
			$imdb_id      = $show->imdbId;
			$show_airtime = date('H:i:s',strtotime($show->airTime));
			$show_airday  = $show->dayOfWeek;
			
			$img = "public/uploads/series/".$tvdbid.".jpg";
			
			$i = 0;
			if(!file_exists($img)){
				while($i <= 4)
				{
					$url = 'http://www.thetvdb.com/banners/posters/'.$tvdbid.'-'.$i.'.jpg';
					$test_url = checkRemoteFile($url);
					if($test_url == true )
					{
						file_put_contents($img, file_get_contents($url));
					}
					$i++;
				}
			}
			
			$show_info['tvdbid']    = $tvdbid;
			$show_info['show_name']    = $show_name;
			$show_info['show_genre']   = $show_genre;
			$show_info['show_desc']    = $show_desc;
			$show_info['show_aired']   = $show_aired;
			$show_info['show_network'] = $show_network;
			$show_info['show_status']  = $show_status;
			$show_info['show_runtime'] = $show_runtime;
			$show_info['imdb_id']      = $imdb_id;
			$show_info['show_airtime'] = $show_airtime;
			$show_info['show_airday']  = $show_airday;
			
		}
		$show_info['action']       = "add_found";
		
		
		
		return $show_info;
	}
	
	public function save(){
		
		$show_no      = $_POST['tvdbid'];
		$show_name    = $_POST['show_name'];
		$show_genre   = $_POST['show_genre'];
		$show_desc    = $_POST['show_desc'];
		$show_aired   = $_POST['show_aired'];
		$show_network = $_POST['show_network'];
		$show_status  = $_POST['show_status'];
		$show_runtime = $_POST['show_runtime'];
		$imdb_id      = $_POST['imdb_id'];
		$show_airtime = $_POST['show_airtime'];
		$show_airday  = $_POST['show_airday'];
		
		if($show_network == 'Revision3'){
			$show_genre   = "Podcasts";
		}
		
		$query = new Model;
		$data = array('show_genre'  => $show_genre,
					  'tvdb_id'     => $show_no,
					  'show_name'   => $show_name,
					  'show_desc'   => $show_desc,
					  'show_aired'  => $show_aired, 
					  'show_network'=> $show_network, 
					  'show_status' => $show_status,
					  'show_runtime'=> $show_runtime,
					  'imdb_id    ' => $imdb_id,
					  'show_airtime'=> $show_airtime,
					  'show_airday' => $show_airday
					  );
					  
		$query->insert_array('shows',$data);
		
		$update = new Show_Model();
		$update->update($show_no);
		
		if(isset($_SESSION['tiwiii_uids8565'])){
			
			$userids = $_SESSION['tiwiii_uids8565'];
			
			$data = array('userid' => $userids, 'action' => "Added show", 'actionid' => $show_no);
			$query->insert_array('userhistory',$data);
			
			if(isset($_SESSION['tiwiii_uids8565']))
			{
				require_once('user_model.php');
				$user = new User_Model;
				$twitopt_chk = $user->twitterupdates("add");
				
				if($twitopt_chk == 'true'){
					echo "Just added ".URL."show/view/$show_no $show_name to @tiwiiiapp";
					
					/*if(isset($_SESSION['tiwiii_uids8565'])){
						require_once('twitter_model.php');
						$tweet = new Twitter_Model;
						$status = "Just added ".URL."show/view/$show_no $show_name to @tiwiiiapp";
						$tweet->tweet($status);
					}*/
				}
			}
		}
		
		$location = "".URL."show/view/$show_no";
		$_SESSION['msg'] = "The show $show_name has been added to Tiwiii";
		header("location:$location");
	}
	
	public function updateshows(){
		
		set_time_limit (0);
		
		$update = new Show_Model();
		
		$query = new Model;
		$sql = "SELECT tvdb_id as show_no from shows WHERE show_status = 'Continuing'";
				 
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);	
				$shows .= $show_no.",";
				
			}
		}
		
		$allshows = explode(',',$shows);
		
		foreach($allshows as $show_no)
		{
			if(!empty($show_no))
			{
				$update->update($show_no);		
			}
		}
		
	}
	
	public function livesearch(){
		
		$query = new Model;
    	$input = filter_var($_GET["q"], FILTER_SANITIZE_STRING);
	 $count_show = 0;
		$sql = " SELECT * FROM shows WHERE show_name LIKE '$input%' LIMIT 3";
		if($query->query($sql))
		{
			$msg .=  " <table id='search-results'><tbody>";
			while($row = $query->get_array())
			{
				$count_show  = $query->get_numrows();
				extract($row);
				if($count_show > 0)
				{
					$show_no = $tvdb_id;
					$show    = new Show_Model;
					$options = $show->user_options($show_no);
					
					extract($options);
					
					if((!isset($_SESSION['tiwiii_uids8565'])))
					{
						$watching = "<li><a alt='Currently Watching' class='remote-buttons' title='Sign in to Tiwiii' id='watching' href='#'>Watching</a></li>";
						$fave     = "<li><a alt='Favorite' class='remote-buttons' title='Sign in to Tiwiii' id='fave' href='#'>Fave</a></li>";
						$like     = "<li><a alt='Like It' class='remote-buttons' title='Sign in to Tiwiii' id='like' href='#'>Like it</a></li>";
					}
					else
					{
						if(!empty($uf_id)){
							$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Remove from your favourites' id='faveselected' href='#$show_no'>Fave</a></li>";
						}else{
							$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Add to your favourites' id='fave'  href='#$show_no'>Fave</a></li>";					
						}
						
						if(!empty($uw_id)){
							$watching = "<li><a alt='$show_name' class='remote-buttons' title='Remove from currently watching' id='watchselected' href='#$show_no'>Watching</a></li>";
						}else{
							$watching = "<li><a alt='$show_name' class='remote-buttons' title='Add to currently watching' id='watching' href='#$show_no'>Watching</a></li>";
						}
						
						if(!empty($uv_id)){
							$like     = "<li><a alt='$show_name' class='remote-buttons' title='Do not like it anymore?' id='dislike' href='#$show_no'>Disike it</a></li>";
						}else{
							$like     = "<li><a alt='$show_name' class='remote-buttons' title='Like it?' id='like' href='#$show_no'>Like it</a></li>";
						}
					}
					
					
					
					$img     = 'public/uploads/series/'.$show_no.'.jpg';
					if (file_exists($img)) {
						$msg .= "
						<tr>
							<td style='border-right:1px solid #eee'> 
							<a class='show-search-thumb' href='".URL."show/view/$tvdb_id'>
								<div class='imgwrap'>
									<img src = '".URL."public/image.php/$tvdb_id.jpg?width=80&amp;image=".URL."public/uploads/series/$tvdb_id.jpg' alt='$show_name'>
								</div> 
							</a>
							</td>
							<td class='search-result-name'>
								<p>$show_name</p>
								 <ul class = 'remote-control' style='margin:50px 0 0 37px;'>
								$like
								$watching
								$fave
								</ul>
							</td>
						</tr>
						";
					}
				}
				
			}
			if ($count_show == 0)
			{
				$msg .= "
					<tr>
						<td style='border-right:1px solid #eee'> 
							<a href='".URL."show/search/'>Add Show</a>								
						</td>
						<td class='search-result-name'>
							<p>$input</p>
						</td>
					</tr>
					";
			}
			$msg .= " </tbody></table>";
		}
		echo $msg;
		
	}
	
	public function fave($showid){
		
		$showid = preg_replace('/[^0-9]/', '', $showid);
		$userid = $_SESSION['tiwiii_uids8565'];
		
		if(!empty($userid) ){
			$query = new Model;
			$data = array('userid'  => $userid,
						  'tvdb_id'     => $showid
						  );
						  
			$sql = "SELECT show_name from shows WHERE tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			$sql = "SELECT count(*) as count_f from user_fave WHERE userid = '$userid' AND tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			if($count_f == 0){
				
				$query->insert_array('user_fave',$data);
				$ufid = mysql_insert_id();
				
				$data = array('userid' => $userid, 'action' => "Favorited", 'actionid' => $showid);
				$query->insert_array('userhistory',$data);
				
				if(isset($_SESSION['tiwiii_uids8565']))
				{
					require_once('user_model.php');
					$user = new User_Model;
					$twitopt_chk = $user->twitterupdates("fave");
					
					if($twitopt_chk == 'true'){
						echo "Just added ".URL."show/view/$showid $show_name to my favorites list on @tiwiiiapp";
						
						/*if(isset($_SESSION['tiwiii_uids8565'])){
							require_once('twitter_model.php');
							$tweet = new Twitter_Model;
							$status = "Just added ".URL."show/view/$showid $show_name to my favorites list on @tiwiiiapp";
							$tweet->tweet($status);
						}*/
					}
				}
				
				
				
			}
		}
		
	}
	
	public function unfave($showid){
		
		$showid = preg_replace('/[^0-9]/', '', $showid);
		$userid = $_SESSION['tiwiii_uids8565'];
		
		if(!empty($userid)){
			$query  = new Model;
			
			$sql = "SELECT show_name from shows WHERE tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			$sql = "SELECT count(*) as count_f from user_fave WHERE userid = '$userid' AND tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			if($count_f == 1){
				
				$sql    = "DELETE FROM user_fave WHERE userid = $userid AND tvdb_id = $showid";
				$result = mysql_query($sql) or die("Can't complete query");
				
				$data = array('userid' => $userid, 'action' => "Removed favorite", 'actionid' => $showid);
				$query->insert_array('userhistory',$data);
				
				if(isset($_SESSION['tiwiii_uids8565']))
				{
					require_once('user_model.php');
					$user = new User_Model;
					$twitopt_chk = $user->twitterupdates("fave");
					
					if($twitopt_chk == 'true'){
						echo "Just removed ".URL."show/view/$showid $show_name from my favorites list on @tiwiiiapp";
						
						/*if(isset($_SESSION['tiwiii_uids8565'])){
							require_once('twitter_model.php');
							$tweet = new Twitter_Model;
							$status = "Just removed ".URL."show/view/$showid $show_name from my favorites list on @tiwiiiapp";
							$tweet->tweet($status);
						}*/
					}
				}
				
				
			
			}
		}
	
	}
	
	public function watch($showid){
		
		$showid = preg_replace('/[^0-9]/', '', $showid);
		$userid = $_SESSION['tiwiii_uids8565'];
		
		if(!empty($userid)){
			$query = new Model;
			$data = array('userid'  => $userid,
						  'tvdb_id'     => $showid
						  );
						  
			$sql = "SELECT show_name from shows WHERE tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			$sql = "SELECT count(*) as count_w from user_watch WHERE userid = '$userid' AND tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			if($count_w == 0){
				$query->insert_array('user_watch',$data);
				$ufid = mysql_insert_id();
				
				$data = array('userid' => $userid, 'action' => "Watching", 'actionid' => $showid);
				$query->insert_array('userhistory',$data);
				
				if(isset($_SESSION['tiwiii_uids8565']))
				{
					require_once('user_model.php');
					$user = new User_Model;
					$twitopt_chk = $user->twitterupdates("watch");
					
					if($twitopt_chk == 'true'){
						echo "Just added ".URL."show/view/$showid $show_name to my currently watching list on @tiwiiiapp";
						
						/*if(isset($_SESSION['tiwiii_uids8565'])){
							require_once('twitter_model.php');
							$tweet = new Twitter_Model;
							$status = "Just added ".URL."show/view/$showid $show_name to my currently watching list on @tiwiiiapp";
							$tweet->tweet($status);
						}*/
					}
				}
							
				
			}
		}
	
	}
	
	public function unwatch($showid){
		
		//$showid = str_replace('#','',$showid);
		$showid = preg_replace('/[^0-9]/', '', $showid);
		$userid = $_SESSION['tiwiii_uids8565'];
		
		if(!empty($userid)){
			$query  = new Model;
			$sql = "SELECT count(*) as count_w from user_watch WHERE userid = '$userid' AND tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			$sql = "SELECT show_name from shows WHERE tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			if($count_w == 1){
				$sql    = "DELETE FROM user_watch WHERE userid = $userid AND tvdb_id = $showid";
				$result = mysql_query($sql) or die("Can't complete query");
				
				$data = array('userid' => $userid, 'action' => "Removed watching", 'actionid' => $showid);
				$query->insert_array('userhistory',$data);
				
				if(isset($_SESSION['tiwiii_uids8565']))
				{
					require_once('user_model.php');
					$user = new User_Model;
					$twitopt_chk = $user->twitterupdates("watch");
					
					if($twitopt_chk == 'true'){
						echo "Just removed ".URL."show/view/$showid $show_name from my currently watching list on @tiwiiiapp";
						
						/*if(isset($_SESSION['tiwiii_uids8565'])){
							require_once('twitter_model.php');
							$tweet = new Twitter_Model;
							$status = "Just removed ".URL."show/view/$showid $show_name from my currently watching list on @tiwiiiapp";
							$tweet->tweet($status);
						}*/
					}
				}
				
				
			}
		}
	
	}
	
	public function vote($showid){
		
		$showid = preg_replace('/[^0-9]/', '', $showid);
		$userid = $_SESSION['tiwiii_uids8565'];
		
		if(!empty($userid)){
			$query = new Model;
			$data = array('userid'  => $userid,
						  'tvdb_id' => $showid
						  );
						  
			$sql = "SELECT show_name from shows WHERE tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			$sql = "SELECT count(*) as count_v from user_vote WHERE userid = '$userid' AND tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			if($count_v == 0){
				$query->insert_array('user_vote',$data);
				$ufid = mysql_insert_id();
				
				$data = array('userid' => $userid, 'action' => "Liked", 'actionid' => $showid);
				$query->insert_array('userhistory',$data);
				
				if(isset($_SESSION['tiwiii_uids8565']))
				{
					require_once('user_model.php');
					$user = new User_Model;
					$twitopt_chk = $user->twitterupdates("like");
					
					if($twitopt_chk == 'true'){
						echo "Just liked ".URL."show/view/$showid $show_name on @tiwiiiapp";
						
							/*if(isset($_SESSION['tiwiii_uids8565'])){
								require_once('twitter_model.php');
								$tweet = new Twitter_Model;
								$status = "Just liked ".URL."show/view/$showid $show_name on @tiwiiiapp";
								$tweet->tweet($status);
							}*/
					}
				}
							
			
			}
		}
	
	}
	
	public function unvote($showid){
		
		$showid = preg_replace('/[^0-9]/', '', $showid);
		$userid = $_SESSION['tiwiii_uids8565'];
		
		if(!empty($userid)){
			$query  = new Model;
			$sql = "SELECT count(*) as count_v from user_vote WHERE userid = '$userid' AND tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			$sql = "SELECT show_name from shows WHERE tvdb_id = '$showid'";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			if($count_v == 1){
				$sql    = "DELETE FROM user_vote WHERE userid = $userid AND tvdb_id = $showid";
				$result = mysql_query($sql) or die("Can't complete query");
				
				$data = array('userid' => $userid, 'action' => "Disliked", 'actionid' => $showid);
				$query->insert_array('userhistory',$data);
				
				if(isset($_SESSION['tiwiii_uids8565']))
				{
					require_once('user_model.php');
					$user = new User_Model;
					$twitopt_chk = $user->twitterupdates("like");
					
					if($twitopt_chk == 'true'){
						echo "Just disliked ".URL."show/view/$showid $show_name on @tiwiiiapp";
						
						/*if(isset($_SESSION['tiwiii_uids8565'])){
							require_once('twitter_model.php');
							$tweet = new Twitter_Model;
							$status = "Just disliked ".URL."show/view/$showid $show_name on @tiwiiiapp";
							$tweet->tweet($status);
						}*/
					}
				}
				
			}
		}
	
	}
	
	public function checkin($epid){
		
		$epid = preg_replace('/[^0-9]/', '', $epid);
		$userid = $_SESSION['tiwiii_uids8565'];
		
		$query  = new Model;
		if(!empty($userid)){
			
			$sql = "SELECT s.show_name, s.tvdb_id as show_id, e.episode_name, e.episode_season, e.episode_number
					FROM   episodes e LEFT JOIN shows s ON s.tvdb_id = e.tvdb_id
					WHERE  e.tvdb_epid = $epid";
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					extract($row);	
									
				}
			}
			
			if($episode_number < 10)
			{$mod_no =  '0'.$episode_number;}else{$mod_no =  $episode_number;}
			
			$epno = $episode_season."".$mod_no;
			
		    $data = array('userid' => $userid, 'action' => "Checked in", 'actionid' => $epid);
			$query->insert_array('userhistory',$data);
			
			if(isset($_SESSION['tiwiii_uids8565']))
			{
				require_once('user_model.php');
				$user = new User_Model;
				$twitopt_chk = $user->twitterupdates("tune");
				
				if($twitopt_chk == 'true'){
					echo "Tuned in to ".URL."show/view/$show_id#$epid $show_name - [$epno] $episode_name (via @tiwiiiapp)";
					
					/*if(isset($_SESSION['tiwiii_uids8565'])){
						require_once('twitter_model.php');
						$tweet = new Twitter_Model;
						$status = "Tuned in to ".URL."show/view/$show_id#$epid $show_name - [$epno] $episode_name (via @tiwiiiapp)";
						$tweet->tweet($status);
					}*/
				}
			}
			
			
		}
	
	}
	
	public function count_vote($showid){
		
		$showid = preg_replace('/[^0-9]/', '', $showid);
		
		$query  = new Model;
		$sql = "SELECT count(*) as count_v from user_vote WHERE tvdb_id = '$showid'";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);	
								
			}
		}
		
		return $count_v;
	}
	
	public function user_options($showid){
		
		$user_options = array();
		$showid = preg_replace('/[^0-9]/', '', $showid);
		$userid = $_SESSION['tiwiii_uids8565'];
		
		$query  = new Model;
		$sql = "SELECT show_name from shows WHERE tvdb_id = '$showid'";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);	
								
			}
		}
		$user_options['show_name'] = $show_name;
		
		$sql = "SELECT count(*) as uv_id from user_vote WHERE tvdb_id = '$showid' AND userid = '$userid'";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);	
								
			}
		}
		
		$user_options['uv_id'] = $uv_id;
		
		$sql = "SELECT count(*) as uf_id from user_fave WHERE tvdb_id = '$showid' AND userid = '$userid'";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);	
								
			}
		}
		$user_options['uf_id'] = $uf_id;
		
		$sql = "SELECT count(*) as uw_id from user_watch WHERE tvdb_id = '$showid' AND userid = '$userid'";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);	
								
			}
		}	
		$user_options['uw_id'] = $uw_id;
		
		return $user_options;
	}
	
	public function show_options($showid, $option){
		
		$showid = preg_replace('/[^0-9]/', '', $showid);
		$option = filter_var($option, FILTER_SANITIZE_STRING);
		$query  = new Model;
		if($option == "likes"){
			$sql = "SELECT u.userid,u.twitid, u.username, u.fullname, u.location, u.fb_id, u.picture
					FROM   shows s LEFT JOIN user_vote uv ON s.tvdb_id = uv.tvdb_id LEFT JOIN users u ON uv.userid = u.userid
					WHERE  s.tvdb_id = $showid
					;";
			$box.="<span id='info-by-likes' class='info-by'>Liked By<a class='close-reveal-modal'>&#215;</a></span>";
		}
		else if($option == "faves"){
			$sql = "SELECT u.userid,u.twitid, u.username, u.fullname, u.location, u.fb_id, u.picture
					FROM   shows s LEFT JOIN user_fave uf ON s.tvdb_id = uf.tvdb_id LEFT JOIN users u ON uf.userid = u.userid
					WHERE  s.tvdb_id = $showid
					;";		
			$box.="<span id='info-by-fave' class='info-by'>Favorited by<a class='close-reveal-modal'>&#215;</a></span>";
		}
		else if($option == "watching"){
			$sql = "SELECT u.userid,u.twitid, u.username, u.fullname, u.location,u.fb_id, u.picture
					FROM   shows s LEFT JOIN user_watch uw ON s.tvdb_id = uw.tvdb_id LEFT JOIN users u ON uw.userid = u.userid
					WHERE  s.tvdb_id = $showid
					;";
			$box.="<span id='info-by-watching' class='info-by'>Currently watched by<a class='close-reveal-modal'>&#215;</a></span>";		
		}
		if($query->query($sql))
		{
			
			$box .= "<div id='lion-bar-box'>";
			while($row = $query->get_array())
			{
				
				extract($row);
				
				if(!empty($twitid))
				{
					$src = "https://api.twitter.com/1/users/profile_image?user_id=".$twitid."&size=normal";
				}
				else
				{
					$src = $picture;
				}
				
				$box .= "<div id='user-options-info'>
							<div id='p-image' style='float:left;'>
								<a href='".URL."user/profile/$username'>
									<img width='48px' height='48px' src='$src' />
								</a>
							</div>
							<div style='float:left; margin:5px 0 0 0;' >
								<p style='font-weight:bold;'>$fullname</p>
								<p>$location</p>
							</div>
						</div>
				";
			}
			$box .= "</div>
			";
		}	
		
		echo $box;
		
	}
	
	
	public function recommendations()
	{
		  $userid = $_SESSION['tiwiii_uids8565'];
		  if(!empty($userid)){
		  $query = new queryDB;
		  $sql   = "SELECT tvdb_id
					FROM   user_fave
					WHERE  userid = $userid
					UNION
					SELECT tvdb_id
					FROM   user_watch
					WHERE  userid = $userid";
		  if($query->query($sql))
		  {
			  while($row = $query->get_array())
			  {
				  extract($row);
				  
				  $my_shows[] = $tvdb_id;
			  }
		  }
		
		  
		  $u_shows = array_unique($my_shows);
		  $my_shows= implode(',',$u_shows);
		   
		 $sql   = "SELECT userid, count(tvdb_id) as sim_count
				   FROM
					  (SELECT userid,tvdb_id
					   FROM   user_fave
					   UNION
					   SELECT userid,tvdb_id
					   FROM   user_watch) as A
					WHERE  tvdb_id IN($my_shows)
					AND  userid != $userid
					GROUP BY userid
					ORDER BY 2 DESC LIMIT 10";
		  if($query->query($sql))
		  {
			  while($row = $query->get_array())
			  {
				  extract($row);
				  $sim_users[] = $userid;
			  }
		  }
		
		  
		  foreach($sim_users as $user)
		  {
			  $sql   = "SELECT tvdb_id
						FROM   user_fave
						WHERE  userid = $user
						UNION
						SELECT tvdb_id
						FROM   user_watch
						WHERE  userid = $user;";
			  if($query->query($sql))
			  {
				  while($row = $query->get_array())
				  {
					  extract($row);
					  ${"sim_shows_".$user}[] = $tvdb_id;
				  }
			  }
			  
					  
			  $r_shows .= implode(',',array_diff(${"sim_shows_".$user}, $u_shows)).',';
		  }
		  $query->closeCon();  
		  
		  $r_shows = explode(',',$r_shows);
		  
		  
		  $dup_shows = array_unique(array_duplicates($r_shows));
		  
		  return $dup_shows;
		  }
	}
	
}