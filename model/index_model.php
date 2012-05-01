<?php

/**
 * tiwiiiClass
 *
 * class description
 * 
 * @author Maail
 */
class Index_Model extends Model
{
	
	public function __construct()
	{		
		//echo "We are in tiwi_model</br>";
		
	}
	
	public function home($type, $page, $filter)
	{
		$query    = new Model();
		
		$previous_btn = true;
	    $next_btn     = true;
	    $first_btn    = true;
	    $last_btn     = true;
	    
	   if($type == 'next')
		{
			$per_page = 5;
		}
		else
		{
			$per_page = 12;
		}
	    $start = ($page-1)*$per_page;
	
	    $cur_page = $page;
		$no_count = NULL;
	    
	    /*----------------------------------------------------------------------*/
		if($type != 'recommendations')
		{
			if($type == 'undefined')
			{
				$sql = " SELECT show_id
					 	 FROM   shows";
			}
			else if($type == 'fall')
			{
				$year     = date('Y');
				$fromdate = ($year-1)."-08-01";
				$todate   = ($year)."-07-31";
				
				
				if($filter == "ended")
				{	
					$sql = " SELECT show_id
							 FROM   shows
							 WHERE  show_aired between '$fromdate' AND '$todate'
							 AND    show_status = 'Ended'";     
				}
				else if($filter == "airing")
				{	
					$sql = " SELECT show_id
							 FROM   shows
							 WHERE  show_aired between '$fromdate' AND '$todate'
							 AND    show_status = 'Continuing'";     
				}
				else
				{
					  $sql = " SELECT show_id
							   FROM   shows
							   WHERE  show_aired between '$fromdate' AND '$todate'";        
				}	
				
			}
			else if($type == 'faved')
			{
				$sql = "SELECT count(uf.tvdb_id) fave_count,s.tvdb_id, s.show_name
						FROM   shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id
						GROUP BY s.tvdb_id
						ORDER BY 1 DESC LIMIT 12";   
			}
			else if($type == 'watched')
			{
				$sql = "SELECT count(uw.tvdb_id) watch_count,s.tvdb_id, s.show_name
						FROM   shows s LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id
						GROUP BY s.tvdb_id
						ORDER BY 1 DESC LIMIT 12";   
			}
			else if($type == 'top'){
			$sql = "SELECT count(uv.tvdb_id) watch_count,s.show_name, s.tvdb_id as s_id
					FROM   shows s LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id
					GROUP BY s.tvdb_id
					ORDER BY 1 DESC
					LIMIT   12";   
			}
			else if($type == 'next')
			{
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				if(!empty($tiwiii_uid))
				{
					$sql = "SELECT s.tvdb_id as s_id, s.show_name, e.episode_name, e.firstaired, e.episode_season, e.episode_number, e.tvdb_epid, e.episode_overview
							FROM   episodes e, shows s LEFT JOIN user_watch uh ON uh.tvdb_id = s.tvdb_id
							WHERE  e.tvdb_id = s.tvdb_id
							AND    e.episode_season !=0
							AND    firstaired > CURDATE()
							AND    uh.userid = '$tiwiii_uid'
							AND    e.episode_season !=0
							GROUP  BY s.tvdb_id
							ORDER BY firstaired ASC";
				}
			}
			else if($type == 'today')
			{
				$sql2 = "SET time_zone = '-5:00'";	
				$query->query($sql2);
				if($filter == "watching")
				{	
					$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
					$sql = " SELECT s.tvdb_id, s.show_name
							  FROM   episodes e, shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid'
							  WHERE  firstaired = CURDATE()
							  AND    e.tvdb_id = s.tvdb_id
							  AND    uw.uw_id IS NOT NULL
							  GROUP BY s.tvdb_id";     
				}
				else
				{
					$sql = " SELECT s.tvdb_id, s.show_name
							  FROM   episodes e, shows s
							  WHERE  firstaired = CURDATE()
							  AND    e.tvdb_id = s.tvdb_id
							  GROUP BY s.tvdb_id";     
				}
			}
			else if($type == 'yesterday')
			{
				$sql2 = "SET time_zone = '-5:00'";	
				$query->query($sql2);
				
				if($filter == "watching")
				{	
					$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
					$sql = " SELECT s.tvdb_id, s.show_name
							  FROM   episodes e, shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid'
							  WHERE  firstaired = date_sub(curdate(),interval 1 day)
							  AND    e.tvdb_id = s.tvdb_id
							  AND    uw.uw_id IS NOT NULL
							  GROUP BY s.tvdb_id";     
				}
				else
				{	
					$sql = " SELECT s.tvdb_id, s.show_name
							  FROM   episodes e, shows s
							  WHERE  firstaired = date_sub(curdate(),interval 1 day)
							  AND    e.tvdb_id = s.tvdb_id
							  GROUP BY s.tvdb_id";  
				}
			}
			else if($type == 'new')
			{
				$sql = " SELECT show_id
						 FROM   shows
						 ORDER BY show_id DESC LIMIT 12";   
			}
			else if($type == 'updates')
			{
				$sql = " SELECT show_id
						 FROM   shows
						 ORDER BY update_date DESC LIMIT 12";   
			}
			if($query->query($sql))
			{
				while($row = $query->get_array())
				{
					$no_count = $query->get_numrows();
					extract($row);		
				}
			}
		}
		else
		{
			if(isset($_SESSION['tiwiii_uids8565']))
			{
				require_once('show_model.php');
				$show = new Show_Model;
				$recommendations = $show->recommendations();
				$no_count = count($recommendations);
			}
		}
	    
		$msg = NULL;
		
		$pages    = ceil($no_count/$per_page);
	    
		$next = $page + 1;
		$prev = $page - 1;		
		
		$msg .= "<ul class='pagination'>";
			
		if(isset($_SESSION['tiwiii_uids8565']))
		{
			if($type == 'yesterday' || $type == 'today')
			{
				
				$schedule_filters = array('all','watching');
			
				foreach($schedule_filters as $sf)
				{
					if($filter == $sf)
					{
						$msg .= "<a href='".URL."home/".$type."/1/".$sf."' class='filters selected' type='".$type."' page='".$page."'  filter='".$sf."' >".strtoupper($sf)."</a>";
					}
					else
					{
						$msg .= "<a href='".URL."home/".$type."/1/".$sf."' class='filters' type='".$type."' page='".$page."'  filter='".$sf."' >".strtoupper($sf)."</a>";
					}
				}
				
				if($filter != "watching")
				{
					/*$msg .= "<li class='filters selected' type='".$type."' page='".$page."'   >All<li>";
					$msg .= "<li class='filters' type='".$type."' page='".$page."'  filter='watching' >Watching<li>";*/
				
				}
				else
				{
					/*$msg .= "<li class='filters' type='".$type."' page='".$page."'   >All<li>";
					$msg .= "<li class='filters selected' type='".$type."' page='".$page."'  filter='watching' >Watching<li>";*/
				}
			}
		}
		
		if($type == 'fall')
		{
			$fall_filters = array('all','airing','ended');
			
			foreach($fall_filters as $ff)
			{
				if($filter == $ff)
				{
					$msg .= "<a href='".URL."home/".$type."/1/".$ff."' class='filters selected' type='".$type."' page='".$page."'  filter='".$ff."' >".strtoupper($ff)."</a>";
				}
				else
				{
					$msg .= "<a href='".URL."home/".$type."/1/".$ff."' class='filters' type='".$type."' page='".$page."'  filter='".$ff."' >".strtoupper($ff)."</a>";
				}
			}
		}

		 
	    $msg .= "</ul>";
	    /*----------------------------------------------------------------------*/
	    
	    if($type == 'undefined'){
	    $sql = " SELECT     show_name, tvdb_id as s_id FROM shows
	             ORDER BY   show_id ASC
	             LIMIT      $start,$per_page";
	    }else if($type == 'faved'){
			if((!isset($_SESSION['tiwiii_uids8565'])))
			{
				$sql = "SELECT count(uf.tvdb_id) fave_count,s.show_name, s.tvdb_id as s_id
						FROM   shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id
						GROUP BY s.tvdb_id
						ORDER BY 1 DESC
						LIMIT    12";   
			}else{
				
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				$sql = "  SELECT af.*, uf.uf_id, uw.uw_id, uv.uv_id
						  FROM
							(SELECT   count(uf.tvdb_id) fave_count,s.tvdb_id as s_id, s.show_name
							 FROM     shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id
							 GROUP BY s.tvdb_id) AS af LEFT JOIN user_fave uf on uf.tvdb_id = af.s_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = af.s_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = af.s_id and uv.userid = '$tiwiii_uid'
						  ORDER BY af.fave_count DESC
						  LIMIT   12 ";			
			}
	    }
		else if($type == 'watched'){
	 
	 		if((!isset($_SESSION['tiwiii_uids8565'])))
			{
				$sql = "SELECT count(uw.tvdb_id) watch_count,s.show_name, s.tvdb_id as s_id
						FROM   shows s LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id
						GROUP BY s.tvdb_id
						ORDER BY 1 DESC
						LIMIT   12";  
			}else{
				
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				$sql = "  SELECT af.*, uf.uf_id, uw.uw_id, uv.uv_id
						  FROM
							(SELECT count(uw.tvdb_id) watch_count,s.show_name, s.tvdb_id as s_id
										  FROM   shows s LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id
										  GROUP BY s.tvdb_id) AS af LEFT JOIN user_fave uf on uf.tvdb_id = af.s_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = af.s_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = af.s_id and uv.userid = '$tiwiii_uid'
						  ORDER BY af.watch_count DESC
						  LIMIT  12 ";			
			}	
				
		}else if($type == 'top'){
	 
	 		if((!isset($_SESSION['tiwiii_uids8565'])))
			{
				$sql = "SELECT count(uv.tvdb_id) vote_count,s.show_name, s.tvdb_id as s_id
						FROM   shows s LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id
						GROUP BY s.tvdb_id
						ORDER BY 1 DESC
						LIMIT   12";  
			}else{
				
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				$sql = "  SELECT af.*, uf.uf_id, uw.uw_id, uv.uv_id
						  FROM
							(SELECT count(uv.tvdb_id) vote_count,s.show_name, s.tvdb_id as s_id
										  FROM   shows s LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id
										  GROUP BY s.tvdb_id) AS af LEFT JOIN user_fave uf on uf.tvdb_id = af.s_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = af.s_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = af.s_id and uv.userid = '$tiwiii_uid'
						  ORDER BY af.vote_count DESC
						  LIMIT  12 ";			
			}	
				
		}else if($type == 'fall'){
			
			$year     = date('Y');
			$fromdate = ($year-1)."-08-01";
			$todate   = ($year)."-07-31";
			
			if((!isset($_SESSION['tiwiii_uids8565'])))
			{
				 $sql = " SELECT     show_name, tvdb_id as s_id FROM shows
						 WHERE      show_aired between '$fromdate' AND '$todate'
						";  
			}else{
				
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				$sql = " SELECT     show_name, s.tvdb_id as s_id, uf.uf_id, uw.uw_id, uv.uv_id
						 FROM       shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
						 WHERE      show_aired between '$fromdate' AND '$todate'
						 ";			
			}
			
			if($filter == "ended")
			{	
				$sql .= " AND    show_status = 'Ended'";     
			}
			else if($filter == "airing")
			{	
				$sql .= " AND    show_status = 'Continuing'";     
			}
			
			$sql.= " ORDER BY   show_id ASC
					 LIMIT      $start,$per_page";
			
	    }
		else if($type == 'today'){
		$sql2 = "SET time_zone = '-5:00'";	
		$query->query($sql2);
			if((!isset($_SESSION['tiwiii_uids8565'])))
			{
				 $sql = " SELECT s.tvdb_id as s_id, s.show_name
						  FROM   episodes e, shows s
						  WHERE  firstaired = CURDATE()
						  AND    e.tvdb_id = s.tvdb_id
						  GROUP BY s.tvdb_id
						  LIMIT      $start,$per_page";   
			}else{
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				
				if($filter == "watching"){
					$filter_sql = "AND    uw.uw_id IS NOT NULL";
				}else{$filter_sql = "";}
				
				$sql = " SELECT s.tvdb_id as s_id, s.show_name, uf.uf_id, uw.uw_id, uv.uv_id
						  FROM   episodes e, shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
						  WHERE  firstaired = CURDATE()
						  AND    e.tvdb_id = s.tvdb_id
						  $filter_sql
						  GROUP BY s.tvdb_id
						  LIMIT      $start,$per_page"; 
				
			}
	    }
		else if($type == 'yesterday'){
		$sql2 = "SET time_zone = '-5:00'";	
		$query->query($sql2);
			
	    
				  
		if((!isset($_SESSION['tiwiii_uids8565'])))
		{
			 $sql = " SELECT s.tvdb_id as s_id, s.show_name
					  FROM   episodes e, shows s
					  WHERE  firstaired = date_sub(curdate(),interval 1 day)
					  AND    e.tvdb_id = s.tvdb_id
					  GROUP BY s.tvdb_id
					  LIMIT      $start,$per_page";  
		}else{
			$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
			
			if($filter == "watching"){
					$filter_sql = "AND    uw.uw_id IS NOT NULL";
				}else{$filter_sql = "";}
				
			$sql = " SELECT s.tvdb_id as s_id, s.show_name, uf.uf_id, uw.uw_id, uv.uv_id
					  FROM   episodes e, shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
					  WHERE  firstaired = date_sub(curdate(),interval 1 day)
					  AND    e.tvdb_id = s.tvdb_id
					   $filter_sql
					  GROUP BY s.tvdb_id
					  LIMIT      $start,$per_page";  
			
		}		  
				  
				 
	    }
	    else if($type == 'new'){
	   		if((!isset($_SESSION['tiwiii_uids8565'])))
			{
				  $sql = " SELECT     show_name, tvdb_id as s_id FROM shows
						   ORDER BY   show_id DESC
						   LIMIT      12 ";   
			}else{
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				 $sql = "  SELECT     show_name, s.tvdb_id as s_id, uf.uf_id, uw.uw_id, uv.uv_id
						   FROM 	  shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
						   ORDER BY   show_id DESC
						   LIMIT      12 ";    
				
			}		  
	    }
	    else if($type == 'updates'){
	   		if((!isset($_SESSION['tiwiii_uids8565'])))
			{
				  $sql = " SELECT     show_name, tvdb_id as s_id FROM shows
						   ORDER BY   update_date DESC
						   LIMIT      12 ";   
			}else{
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				 $sql = "  SELECT     show_name, s.tvdb_id as s_id, uf.uf_id, uw.uw_id, uv.uv_id
						   FROM 	  shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
						   ORDER BY   update_date DESC
						   LIMIT      12 ";    
				
			}	  
	    } else if($type == 'next'){
			$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
			if(!empty($tiwiii_uid)){
				$sql = "SELECT * FROM (SELECT s.tvdb_id as s_id, s.show_name, e.episode_name, e.firstaired, e.episode_season, e.episode_number, e.tvdb_epid, e.episode_overview, s.show_airtime
						FROM   episodes e, shows s LEFT JOIN user_watch uh ON uh.tvdb_id = s.tvdb_id
						WHERE  e.tvdb_id = s.tvdb_id
						AND    e.episode_season !=0
						AND    concat(firstaired,' ',show_airtime) > CURDATE()
						AND    uh.userid = '$tiwiii_uid'
						ORDER BY episode_season, episode_number ASC) as A
						GROUP BY A.s_id
						ORDER BY A.firstaired ASC 
						LIMIT  $start,$per_page";
			}
			else
			{
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				$msg = "<p style='margin: 35px 0 0 20px; font-size: 15px; font-weight: bold; color: #A9ACB1;'>Add shows to you currently watching list to see when the next episodes are going to come out.";
				if(empty($tiwiii_uid)){
					$msg .= "<span style='color: #349DE1;'> But first looks like your going to have to sign in.</span>";
				}
				
				$msg.="</p>";
			}
		}
		if($type != 'recommendations'){
	    if($query->query($sql))
	    {
	    	$msg .=  "<div id='show-tiles'>";
	    	while($row = $query->get_array())
	    	{
	    		extract($row);
	    		$show_no = $s_id;
	    		$img     = 'public/uploads/series/'.$show_no.'.jpg';
				
				if($type != 'next')
				{
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
							$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Add to your favourites' id='fave' href='#$show_no'>Fave</a></li>";					
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
					
					
					
					if($type == 'faved'){
						$option_count = "<div id='fave_count'>
											<img id='fave_count_l' src='".URL."public/images/heart5.png'>
											<span id='fave_count_n'>$fave_count</span> 
										</div>";
					}
					elseif($type == 'watched'){
						$option_count = "<div id='watch_count'>
											<img  id='watch_count_l' src='".URL."public/images/eye4.png'>
											<span id='watch_count_n' >$watch_count</span> 
										 </div>";
					}elseif($type == 'top'){
						$option_count = "<div id='vote_count'>
											<img  id='vote_count_l' src='".URL."public/images/like_w.png'>
											<span id='vote_count_n' >$vote_count</span> 
										 </div>";
					}
					
					require_once('show_model.php');
					$show  = new Show_Model;
					$votes = $show->count_vote($s_id);
					
					$vote_no = "<a id='circle' href='".URL."show/view/$s_id'><p>$votes</p></a>";
					
					if (file_exists($img)) {
						$msg .= "<div class='show-thumb'>
									  <div class='imgwrap'>
										  <div class='span'>
											  <h1 id='test'>$show_name</h1>
											  $vote_no
											  <ul class = 'remote-control'>
												  $like
												  $watching
												  $fave
											  </ul>
										  </div>
										  <a href='".URL."show/view/$s_id'>
											  <img src = '".URL."public/image.php/$s_id.jpg?width=120&amp;image=".URL."public/uploads/series/$s_id.jpg' class='poster'>
										  </a>
									  </div> 
									  $option_count
									  
								  </div>
								  ";
					}
				}
				else
				{
				
				$query2= new Model;
					
				$sql = " SELECT timediff(now(),convert_tz(now(),@@session.time_zone,'+00:00')) as time_diff;";
		
				if($query2->query($sql))
				{
					while($row = $query2->get_array())
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
					
					
				$hisdate_rep = str_replace('-','/',$firstaired)." ".$show_airtime;
				
				/*$date = '8:00';
				echo date('H:i:s', strtotime($date));*/
				
				if($episode_number < 10)
				{$mod_no =  '0'.$episode_number;}else{$mod_no =  $episode_number;}
				
				 $msg .= 
					"<div id='action''><span id='time-diff' style='display:none;'>$mins</span>
					 	 <a href='".URL."show/view/$s_id' style='float:left; width:100px;'>
								<img src = '".URL."public/image.php/$s_id.jpg?width=80&amp;image=".URL."public/uploads/series/$s_id.jpg' class='poster'>
						 </a>
						 <span style='float:right;font-size:11px;color:#349de1;font-weight:bold;' class='in-date' >$hisdate_rep</span>
						 <p style='font-weight:bold;display:block;font-size:12px;margin:0 0 5px 0; padding:0;'>$show_name </p>
						 <p id='' style='font-weight:bold; margin:0 0 5px 0; padding:0;'>$episode_season$mod_no - $episode_name</p>
						 <p style='display:block;'>$episode_overview</p>
					</div>
					";
				
				
				}
	    	}
	    	$msg .= "</div>";
			
		}
		$filter_home = array('fall'=>'Fall 2011-2012',
							 'recommendations'=>'Recommendations',
							 'schedule'=>'Schedule',
							 'today'=>'Airing Today',
							 'yesterday'=>'Aired Yesterday',
							 'top'=>'Top Rated',
							 'faved'=>'Most Favorited',
							 'watched'=>'Most Watched',
							 'new'=>'Newly Added',
							 'updates'=>'Recently Updated');

		$msg .= "<div id='filter-genre'><ul>";
		foreach($filter_home as $fh=>$value){
			if($type == $fh)
			{
				$msg.="<li><a href='".URL."home/$fh/1' id='".$fh."' class='selected'>$value</a></li>";
			}
			else
			{
				$msg.="<li><a href='".URL."home/$fh/1' id='".$fh."'>$value</a></li>";
			}
		}
		
		
		$msg.= "<li><a class='add-new' href='".URL."show/search/'>Add a Show</a></li>";
		$msg.= "</ul></div>";
		
		
		
		 
		 $msg .= "<ul class='pagination'>";
					if($next <= $pages)$msg .= "<li><a href='".URL."home/".$type."/".$next."/".$filter."' class='next-page'>Next</a></li>";
					if($pages>1)if($page <= $pages) $msg .= "<li class='page-info'>$page of $pages</li>";
					if($prev > 0)$msg .= "<li><a href='".URL."home/".$type."/".$prev."/".$filter."' class='prev-page'>Prev</a></li>";
				$msg .= "</ul>";
		 
		}
		else
		{
			require_once('show_model.php');
			$show = new Show_Model;
			$recommendations = $show->recommendations();
			if(!empty($recommendations))
			{
				$reco_chunks = array_chunk($recommendations, 12);
				$msg .=  "<div id='show-tiles'>";
				
				foreach($reco_chunks[$page-1] as $s_id)
				{
					
					$show_no = $s_id;
					$img     = 'public/uploads/series/'.$show_no.'.jpg';
				
					$user_opts = $show->user_options($s_id);
					extract($user_opts);
				
				
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
							$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Add to your favourites' id='fave' href='#$show_no'>Fave</a></li>";					
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
					
					if($type == 'faved'){
						$option_count = "<div id='fave_count'>
											<img id='fave_count_l' src='".URL."public/images/heart5.png'>
											<span id='fave_count_n'>$fave_count</span> 
										</div>";
					}
					elseif($type == 'watched'){
						$option_count = "<div id='watch_count'>
											<img  id='watch_count_l' src='".URL."public/images/eye4.png'>
											<span id='watch_count_n' >$watch_count</span> 
										 </div>";
					}elseif($type == 'top'){
						$option_count = "<div id='vote_count'>
											<img  id='vote_count_l' src='".URL."public/images/like_w.png'>
											<span id='vote_count_n' >$vote_count</span> 
										 </div>";
					}
					
					$votes = $show->count_vote($s_id);
					
					$vote_no = "<a id='circle' href='".URL."show/view/$s_id'><p>$votes</p></a>";
					
					if (file_exists($img)) {
						$msg .= "<div class='show-thumb'>
									  <div class='imgwrap'>
										  <div class='span'>
											  <h1 id='test'>$show_name</h1>
											  $vote_no
											  <ul class = 'remote-control'>
												  $like
												  $watching
												  $fave
											  </ul>
										  </div>
										  <a href='".URL."show/view/$s_id'>
											  <img src = '".URL."public/image.php/$s_id.jpg?width=120&amp;image=".URL."public/uploads/series/$s_id.jpg' class='poster'>
										  </a>
									  </div> 
									  $option_count
									  
								  </div>
								  ";
					}
				}
			
			
				$next= $page+1;
				$prev = $next - 2;
				
				
				
				$msg .= "<ul class='pagination'>";
					if($next <= $pages)$msg .= "<li><a href='".URL."home/".$type."/".$next."/".$filter."' class='next-page'>Next</a></li>";
					if($pages>1)if($page <= $pages) $msg .= "<li class='page-info'>$page of $pages</li>";
					if($prev > 0)$msg .= "<li><a href='".URL."home/".$type."/".$prev."/".$filter."' class='prev-page'>Prev</a></li>";
				$msg .= "</ul>";
				
				
			}
			else
			{
				$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				$msg = "<p style='margin: 35px 0 0 20px; font-size: 15px; font-weight: bold; color: #A9ACB1;'>Add shows to your Currently Watching and Favorites list to get your personalized recommendations.";
				if(empty($tiwiii_uid)){
					$msg .= "<span style='color: #349DE1;'> But first looks like your going to have to sign in.</span>";
				}
				
				$msg.="</p>";
			}
			
		}
		
		
		
		
	    /*----------------------------------------------------------------------*/
	    $msg .= "<script>
	    $('.show-thumb .span, .poster').hover(
	    	function(){
	    	    $(this).closest('.imgwrap').find('img.poster').css({'background' : '#000', 'opacity' : '.13'});
	    	},
	    	function(){
	    	    $(this).closest('.imgwrap').find('img.poster').css({'background' : '', 'opacity' : ''});
	    	}
	    );
	    // tool tip
	    $(function(){   
	    	$('.remote-buttons').tipTip({defaultPosition:'top'});
	    });	
		
		$(document).ready(function()
		{
			var db_time   = $('#time-diff').text();
			var offset    = moment().zone(); 
			var time_diff =  Number(db_time) - Number(offset);
							
			$('.in-date').each(function(i, obj){						
				var date     = $(obj).text();										
				var mom      = moment(date).add('m',time_diff);	
				var timegone = mom.fromNow();
				timegone = timegone.replace('in', '')				
				$(obj).text(timegone);
			});
		});
			
		
	    </script>
	    ";
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
		{
			echo $msg;
		}
		else
		{
	    	return $msg;
		}
	}
	
	public function genre($type, $page, $filter)
	{
		$query    = new Model();
		
		$previous_btn = true;
	    $next_btn     = true;
	    $first_btn    = true;
	    $last_btn     = true;
	    
	    $per_page = 12; 
	    $start    = ($page-1)*$per_page;
		
		$showdash = array('science-fiction','mini-series');
		if(!(in_array($type,$showdash))){$type = str_replace( '-', ' ', $type);}
	
	    $cur_page = $page;
	    
	    /*----------------------------------------------------------------------*/
	    if($type == 'all'){
	    $sql = " SELECT count(*) as no_count
	             FROM   shows";
	    }else{
	    $sql = " SELECT count(*) as no_count
	    		 FROM   shows
	    		 WHERE  show_genre LIKE '%$type%'";   
	    }
	    if($query->query($sql))
	    {
	        while($row = $query->get_array())
	    	{
	    		$no_count = $query->get_numrows();
	    		extract($row);		
	    	}
	    }
	    
	    $per_page = 12; 
	    $pages    = ceil($no_count/$per_page);
	    
	    $msg = NULL;
		
		$next = $page + 1;
		$prev = $page - 1;
		
	
	    /*----------------------------------------------------------------------*/
	    if($type == 'all'){
			if((!isset( $_SESSION['tiwiii_uids8565'])))
			{
				$sql = " SELECT     show_name, tvdb_id as s_id FROM shows
					     ORDER BY   show_id ASC
					     LIMIT      $start,$per_page";
			}else{
				 $tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				 $sql = " SELECT     show_name, s.tvdb_id as s_id, uf.uf_id, uw.uw_id, uv.uv_id
				           FROM       shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
					       ORDER BY   show_id ASC
					       LIMIT      $start,$per_page";
			}
	    }else{
		if((!isset( $_SESSION['tiwiii_uids8565'])))
			{
				$sql = " SELECT     show_name, tvdb_id as s_id FROM shows
						 WHERE      show_genre LIKE '%$type%'
						 ORDER BY   show_id ASC
						 LIMIT      $start,$per_page ";
			}else{
				 $tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				 $sql = " SELECT     show_name, s.tvdb_id as s_id, uf.uf_id, uw.uw_id, uv.uv_id
						 FROM 		shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
						 WHERE      show_genre LIKE '%$type%'
						 ORDER BY   show_id ASC
						 LIMIT      $start,$per_page ";
			}
	    }    
	    if($query->query($sql))
	    {
	    	$msg .=  "<div id='show-tiles'>";
	    	while($row = $query->get_array())
	    	{
	    		extract($row);
	    		$show_no = $s_id;
	    		$img     = 'public/uploads/series/'.$show_no.'.jpg';
				
				if((!isset( $_SESSION['tiwiii_uids8565'])))
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
						$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Add to your favourites' id='fave' href='#$show_no'>Fave</a></li>";					
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
				
				require_once('show_model.php');
				$show  = new Show_Model;
				$votes = $show->count_vote($s_id);
				
				$vote_no = "<a id='circle' href='".URL."show/view/$s_id'><p>$votes</p></a>";
				
	    		if (file_exists($img)) {
	    			$msg .= " <div class='show-thumb'>
	    						<div class='imgwrap'>
	    						<div class='span'>
	    							<h1 id='test'>$show_name</h1>
									$vote_no
	    							<ul class = 'remote-control'>
	    								$like
										$watching
										$fave
	    							</ul>
	    						</div>
	    							<a href='".URL."show/view/$s_id'>
	    								<img src = '".URL."public/image.php/$s_id.jpg?width=120&amp;image=".URL."public/uploads/series/$s_id.jpg' class='poster'>
	    							</a>
	    						</div> 
	    					  </div>
	    					  ";
	    		}
	    	}
	    	$msg .= "</div>";
	    }
		
		
        $filter_genre[] = "all";
		$sql = " SELECT genre as current_gen from genres";
    	if($query->query($sql))
    	{   
            while($row = $query->get_array())
    		{
    			extract($row);
    			$href= str_replace(' ', '-', $current_gen);
				$href = strtolower($href);
				$filter_genre[] = $href;
                				
    		}
    	}    		
		
		
		$msg .= "<div id='filter-genre'><ul>";
		foreach($filter_genre as $fg){
			if($type == $fg)
			{
				$msg.="<li><a href='".URL."genre/$fg/1' id='".$fg."' class='selected'>$fg</a></li>";
			}
			else
			{
				$msg.="<li><a href='".URL."genre/$fg/1' id='".$fg."'>$fg</a></li>";
			}
		}
		
		
		$msg.= "<li><a class='add-new' href='".URL."show/search/'>Add a Show</a></li>";
		$msg.= "</ul></div>";	
			
		
		$msg .= "<ul class='pagination'>";
					if($next <= $pages)$msg .= "<li><a href='".URL."genre/".$type."/".$next."' class='next-page'>Next</a></li>";
					if($pages>1)if($page <= $pages) $msg .= "<li class='page-info'>$page of $pages</li>";
					if($prev > 0)$msg .= "<li><a href='".URL."genre/".$type."/".$prev."' class='prev-page'>Prev</a></li>";
		$msg .= "</ul>";
	    /*----------------------------------------------------------------------*/
	    $msg .= "<script>
	        $('.show-thumb .span, .poster').hover(
	    	function(){
	    	    $(this).closest('.imgwrap').find('img.poster').css({'background' : '#000', 'opacity' : '.13'});
	    	},
	    	function(){
	    	    $(this).closest('.imgwrap').find('img.poster').css({'background' : '', 'opacity' : ''});
	    	}
	    );
	    // tool tip
	    $(function(){   
	    	$('.remote-buttons').tipTip({defaultPosition:'top'});
	    });
		
	    </script>
	    ";
	    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
		{
			echo $msg;
		}
		else
		{
	    	return $msg;
		}
	}
}