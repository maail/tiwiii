	<div id="wrapper">
		<div id="header">
			<a href="/" id="logo" ></a>
			<?php include ('nav.php'); ?>
		</div>
         <?php include('feedback.php');?>
		<?php include ('tv-rack.php'); ?>
		<?php
			$row = $this->showinfo;
			//print_r($row);
			extract($row);
			if($action == 'show_info'){
		?>
        <div id="main">
        <ul class="show-nav">
       			<li id="overview" class="selected">Overview</li>
                <li id="activity" show="<?php echo $tvdb_id;?>">Activity</li>
                <li id="review">Reviews</li>
                <li id="media">Media</li>
        </ul>
       		<div id="the-show">
				<div id="show-poster">
                	<?php 
						if($show_status == 'Ended'){?><div id="status"></div><?php } 
						if(!empty($show_airtime)){$s_a = " at $show_airtime";}else{$s_a="";}
						if(empty($show_airday)){$show_airday = "TBA";}
						
						if((!isset($_SESSION['tiwiii_uids8565'])))
				{
					$watching = "<a alt='Currently Watching' class='remote-buttons' title='Sign in to Tiwiii' id='eye-show' href='#'>Watching</a>";
					$fave     = "<a alt='Favorite' class='remote-buttons' title='Sign in to Tiwiii' id='heart-show' href='#'>Fave</a>";
					$like     = "<a alt='$show_name' class='remote-buttons' title='Sign in to Tiwiii' id='like-g' href='#'>Like it</a>";
				}
				else
				{
					$tiwiii_uid = $_SESSION['tiwiii_uids8565'];
					$query = new queryDB;
					$sql = " SELECT  uf.uf_id, uw.uw_id, uv.uv_id
						 	 FROM    shows s LEFT JOIN user_fave uf on uf.tvdb_id = s.tvdb_id and uf.userid = '$tiwiii_uid' LEFT JOIN user_watch uw on uw.tvdb_id = s.tvdb_id and uw.userid = '$tiwiii_uid' LEFT JOIN user_vote uv on uv.tvdb_id = s.tvdb_id and uv.userid = '$tiwiii_uid'
							 WHERE     s.tvdb_id = $tvdb_id;
						 ";
					if($query->query($sql))
					{
						while($row = $query->get_array())
						{
							extract($row);
						}
					}
												
					if(!empty($uf_id)){
						$fave     = "<a alt='$show_name'  class='remote-buttons' title='Remove from your favourites' id='heart-show-selected' href='#$tvdb_id'>Fave</a>";
					}else{
						$fave     = "<a alt='$show_name'  class='remote-buttons' title='Add to your favourites' id='heart-show' href='#$tvdb_id'>Fave</a>";					
					}
					
					if(!empty($uw_id)){
						$watching = "<a alt='$show_name' class='remote-buttons' title='Remove from currently watching' id='eye-show-selected' href='#$tvdb_id'>Watching</a>";
					}else{
						$watching = "<a alt='$show_name' class='remote-buttons' title='Add to currently watching' id='eye-show' href='#$tvdb_id'>Watching</a>";
					}
					
					if(!empty($uv_id)){
						$like     = "<a alt='$show_name' class='remote-buttons' title='Do not like it anymore?' id='dislike-g' href='#$tvdb_id'>Disike it</a>";
					}else{
						$like     = "<a alt='$show_name' class='remote-buttons' title='Like it?' id='like-g' href='#$tvdb_id'>Like it</a>";
					}
				}
					?>
					<?php echo $the_selected_poster;?>
				</div>
                
				<div class="shows">
                
					<div id="show-info">
						<h3 style="float:left;" id="show-name"><?php echo $show_name; ?></h3>
                         
                        <div style="clear:both;"></div>
						<p id="show-desc"><?php echo $show_desc; ?></p>
						<p><strong>Network : </strong><?php echo $show_network ?></p>
						<p><strong>Air Time :  </strong><?php echo $show_airday.$s_a;?></p>
					</div>
					<div style="clear:both;"></div>
                   
					
				</div>
                <div id="options" style="float:right;width:340px;margin: -20px 0 0 229px;position: absolute;z-index: 2;">
                     <div id="share-options" style="float:left;">
                    <a style="float:left;" href="http://pinterest.com/pin/create/button/?description=<?php echo $show_name; ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
                    <div class="fb-like" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false" data-action="like" style="margin: 0 0 0 5px;
float: left;"></div>
<a href="https://twitter.com/share" class="twitter-share-button" data-via="tiwiiiapp" data-hashtags="tiwiii">Tweet</a>
                    </div>
                    
                        <div id="show-options" style="float:right;">                   
                        <?php if($show_status != 'Ended'){ ?>
                        <a id='update-show' class="remote-buttons" title='Update show information' href='#Update'>Update Show</a>
                        <?php } 
                            echo $fave;
                            echo $watching;
                            echo $like;
                        ?>
                        </div>
                    </div>
             </div>
             <ul id="stats" style="">
             	<li style=" background: #b4cc66; border:solid 1px #86A20A; border-bottom:solid 2px #86A20A; border-top:solid 2px #86A20A;">
                        <a href="#<?php if($count_uv > 0){echo $tvdb_id;} else{ echo "";}?>" <?php  if($count_uv > 0){echo " class='big-link'" ;} ?>  id="like-users">
                            <img  id='vote_count_l' src="<?php echo URL; ?>public/images/like_w.png">
                            <span id='vote_count_n' ><?php echo $count_uv; ?></span> 
                        </a>
                </li>
                  <li style="background: #3496EB; border:solid 1px #2A7EB5; border-bottom:solid 2px #2A7EB5; border-top:solid 2px #2A7EB5;">
                         <a href="#<?php if($count_uw > 0){echo $tvdb_id; } else{ echo "";}?>" <?php  if($count_uw > 0){echo " class='big-link' ";} ?> id="watch-users" >
                            <img  id='watch_count_l' src="<?php echo URL; ?>public/images/eye4.png">
                            <span id='watch_count_n' ><?php echo $count_uw; ?></span> 
                        </a>
                </li>
                <li style="background: #D05F57; border:solid 1px #A33F3F; border-bottom: solid 2px #A33F3F; border-top: solid 2px #A33F3F;">
                         <a href="#<?php if($count_uf > 0){echo $tvdb_id; } else{ echo "";}?>" <?php  if($count_uf > 0){echo " class='big-link'";} ?> id="fave-users" >
                            <img  id='fave_count_l' src="<?php echo URL; ?>public/images/heart5.png">
                            <span id='fave_count_n' ><?php echo $count_uf; ?></span> 
                        </a>
                </li>
                
            </ul>
            
            <div id="myModal" class="reveal-modal">
			
			</div>
            
            
			<?php if(!empty($count_show2) || $count_show2 != 0){ ?>
            <?php 
				if($episode_number < 10)
				{$mod_no =  '0'.$episode_number;}else{$mod_no =  $episode_number;}
				 if($firstaired == "1970-01-01" || $firstaired == "1969-12-31" ){$firstaired = "TBA";}
				 
				 if($show_status == 'Ended'){$episode_info = "Last Episode";}else{$episode_info = "Next Episode";}
			?>
           <div id="container"></div>
			<div id="the-show-ep" style="margin:40px 0 0 10px;">
             <span id="episode-info"><?php echo $episode_info; ?></span>
				<div class="shows">
					<div id="show-info" style="width:98%; margin:10px 0 0 0;">
						<h3><?php echo "$episode_season$mod_no - $episode_name"; ?></h3>
						<p><?php echo $episode_overview; ?></p>
						<?php if($firstaired > date('Y-m-d',strtotime("Now"))){ $airs = "Airing On : ";} else { $airs = "Aired On : ";}?>
						<?php if(!empty($firstaired)){?><p><strong><?php echo $airs; ?></strong><?php echo $firstaired; ?></p><?php } ?>
						<?php if(!empty($gueststars)){?><p><strong>Guest Stars : </strong><?php echo $gueststars; ?></p><?php } ?>
						<?php if(!empty($directors)){?><p><strong>Directors : </strong><?php echo $directors; ?></p><?php } ?>
						<?php if(!empty($writers)){?><p><strong>Writers : </strong><?php echo $writers; ?></p><?php } ?>
						
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php echo $accordian; ?>
        <?php }
		else if($action == 'add_blank')
		{ ?>
        
        <div id="main">
			<div id="show-form">
			<h3>Add Show Information to Tiwiii</h3>
            <form method="post" id="search_tvdb" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
            
                <label for="Show Name">Search Show</label>
                <input type="text" name="search_shows" />
                
                <div style="clear:both;"></div>
                <input type="submit" name="submit" class="button" id="stvdb" value="Search" />	
                
            </form>
            <div id="msg-div"></div>
            <div style="clear:both;"></div>
            <div id="result-div"></div>
		<?php
        } else if($action == 'add_found'){?>
			<div id="main">
			<div id="show-form">
        <?php	  if(isset($result)){
				  echo $result;
			  }else{
        ?>
        
        <h3>Add Show Information to Tiwiii</h3>
        <form method="post" action="<?php echo URL;?>show/save/">
            
            <?php
               $img = 'public/uploads/series/'.$tvdbid.'.jpg';
               if (file_exists($img)) {
                	echo "<img src = '".URL."public/image.php/$tvdbid.jpg?width=220&amp;image=".URL."public/uploads/series/$tvdbid.jpg' class='add-poster'>";
					$status = "ok";
			   }else{$status = "not";}
			  	
            ?>
            
            <?php if($status == 'not'){ 
					echo "<span style ='color: #7B7981; margin: 5px 0 5px 0; float:left; color:#D8151C;'>This show cannot be added since it has no artwork. We'll give the option of adding artwork later on.</span>
		<div style='clear:both;'></div>";} ?>
            
            <div style="float:left;margin:0 0 5px 0;">
                <label for="Show Name">Show Name</label>
                <input type="text" name="show_name" value="<?php if(isset($show_name))echo $show_name; ?>" readonly="readonly" />
            </div>
            
            <div style="float:left; margin:0 10px 5px 5px;">
                <label for="Show Genre">Show Genre</label>
                <input type="text" name="show_genre" value="<?php if(isset($show_genre))echo $show_genre; ?>" readonly="readonly"/>
            </div>
            
            <label for="Show Description">Show Description</label>
            <textarea name="show_desc" readonly="readonly"><?php if(isset($show_desc))echo $show_desc; ?></textarea>
            
            <div style="float:left;">
                <label for="Show Network">Show Network</label>
                <input type="text" name="show_network" value="<?php if(isset($show_network))echo $show_network; ?>" readonly="readonly"/>
                
                <label for="Show Aired">Show Aired</label>
                <input type="text" name="show_aired" value="<?php if(isset($show_aired))echo $show_aired; ?>" readonly="readonly"/>
                
                <label for="Show Status">Show Status</label>
                <input type="text" name="show_status" value="<?php if(isset($show_status))echo $show_status; ?>" readonly="readonly"/>
            </div>
            
            <div style="float:left; margin:0 10px 0 5px;">
                <label for="Show Runtime">Show Runtime</label>
                <input type="text" name="show_runtime" value="<?php if(isset($show_runtime))echo $show_runtime; ?>" readonly="readonly"/>
                
                <label for="Show Airtime">Show Airtime</label>
                <input type="text" name="show_airtime" value="<?php if(isset($show_airtime))echo $show_airtime; ?>" readonly="readonly"/>
                
                <label for="Show Airday">Show Airday</label>
                <input type="text" name="show_airday" value="<?php if(isset($show_airday))echo $show_airday; ?>" readonly="readonly"/>
            </div>
            
            <input type="hidden" name="imdb_id"value="<?php if(isset($imdb_id))echo $imdb_id; ?>" readonly="readonly"/>
            <input type="hidden" name="tvdbid" value="<?php if(isset($tvdbid))echo $tvdbid; ?>" readonly="readonly"/>
            <input type="hidden" name="tvdbid" value="<?php if(isset($tvdbid))echo $tvdbid; ?>" readonly="readonly"/>
            <?php if($status == 'ok'){ ?> <input type="submit" class="button" value="Add Show" onClick="$(this).click(function() {return false;});"/>
            <?php } ?>
        	
        </form>
        <?php } }?>
			</div>
        </div>
        </div>
        
   <script src="<?php echo URL; ?>public/js/pagination-as.js" type="text/javascript"></script>   
<script>
$(function(){   
	$(".remote-buttons").tipTip({defaultPosition:"top"});
});



$(document).ready(function()
{	
	var pagetitle = document.title;
	$(window).hashchange( function(){
     	
		var epid_hash = location.hash;
		var epid_hash =(epid_hash.replace(/\/?#/, ""));
		var epid      = epid_hash.match(/[0-9 -()+]+$/);
		
		var intRegex = /^\d+$/;
		//alert(epid);
		if(intRegex.test(epid))
		{
			if(epid != ""){
				$('#container').hide();
				$.ajax({
						url: "http://tiwiii2.local/show/episode/"+epid+"",
						type: "POST",
						success: function(msg){
							 $('#the-show-ep').replaceWith( msg );
							 var ep_name = $('#ep-name').text();
							 document.title= pagetitle + " / " + ep_name;
						}
				 });
			}
		}
	});
	
	$(window).hashchange();
	
	$(function(){   
		$('.checkin-buttons').tipTip({defaultPosition:'left'});
	});
	
	
	$('.check-in').click(function(){ 
		  var epid_hash	   = $(this).attr("alt");
		  var epid         =(epid_hash.replace(/\/?#/, ""));
		  var t 		   = $(this).closest('#checkin-ep').text();
		  var checkin_info = $.trim(t.replace("Check In",""));
		  var show_name    = $('#show-name').text();
		  var checkin 	   = show_name+" - "+checkin_info;
		  
		  if(epid != ""){
			  
			  var feedback     = $('#feedback').text();
			  if(feedback == ""){
				  $('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
			  }
			  $('#feedback').text("You have checked in to "+checkin+".");
			  
			  $.ajax({
					  url: "http://tiwiii2.local/show/checkin/"+epid+"",
					  type: "POST"
			   });
			}
	});
	
	
	
});
</script>

