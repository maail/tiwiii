<?php

/**
 * TV_Model
 *
 * Model for Class Shows
 * 
 * @author Maail
 */
class TV_Model extends Model
{
	
	public function __construct()
	{		
				
	}
	
	public function shows($genre, $page)
	{
		$query    = new Model();
		
		$previous_btn = true;
	    $next_btn     = true;
	    $first_btn    = true;
	    $last_btn     = true;
	    
	    $per_page = 12; 
	    $start    = ($page-1)*$per_page;
		
		$showdash = array('science-fiction','mini-series');
		if(!(in_array($genre,$showdash))){$genre = str_replace( '-', ' ', $genre);}
	
	    $cur_page = $page;
	    
	    /*----------------------------------------------------------------------*/
	    if($genre == 'all'){
	    $sql = " SELECT count(*) as no_count
	             FROM   shows";
	    }else{
	    $sql = " SELECT count(*) as no_count
	    		 FROM   shows
	    		 WHERE  show_genre LIKE '%$genre%'";   
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
	    if($genre == 'all'){
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
						 WHERE      show_genre LIKE '%$genre%'
						 ORDER BY   show_id ASC
						 LIMIT      $start,$per_page ";
			}else{
				 $tiwiii_uid = $_SESSION['tiwiii_uids8565'];
				 $sql = " SELECT     show_name, s.tvdb_id as s_id, uf.uf_id, uw.uw_id, uv.uv_id
						 FROM 		shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
						 WHERE      show_genre LIKE '%$genre%'
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
		
			
	    
	    $msg .= "<ul class='pagination'>";
			 
		 if($next <= $pages)$msg .= '<li  genre="'.$genre.'" page="'.$next.'" class="next-page">Next</li>';
		 if($page <= $pages) $msg .= "<li class='page-info'>$page of $pages</li>";
		 if($prev > 0)$msg .= '<li  genre="'.$genre.'" page="'.$prev.'" class="prev-page">Prev</li>';
		 
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
	    echo $msg;
			
	}
}