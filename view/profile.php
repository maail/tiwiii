	<div id="wrapper">
    	<div id="header">
		<a href="/" id="logo" ></a>
			<?php include ('nav.php'); ?>
		</div>
         <?php echo "<div id='feedback' style='display:none;margin: 62px 0 0 255px; width: 673px;'></div>";?>
		<?php include ('tv-rack.php'); ?>
		<span id='loading'> </span>
		<div id="main" style="width:780px;">
			<?php 
			require_once('../model/show_model.php');
			$show  = new Show_Model;
			
			$row = $this->userinfo;
				
			$user_info = $row[0];
			$user_fave = $row[1];
			$user_watch = $row[2];
			
			$no_fave = count($user_fave);
			$no_watch = count($user_watch);
			
			extract($user_info);
			?>
            <div id="user-profile" >
            	<div id="p-image">
            		<?php
						if(!empty($tids))
						{
							$src = "https://api.twitter.com/1/users/profile_image?user_id=".$tids."&size=bigger";
						}
						else
						{
							$src = $pic;
						}
					?>
                    <?php echo "<img width='72px' height='72px' src='$src' />"; ?>
                    <?php //echo "<img style='position:absolute;left:-10px;top:-10px;'src='http://profile.ak.fbcdn.net/hprofile-ak-snc4/186189_507735523_941289_s.jpg' />"; ?>
                </div>
                <ul>
                	<li id="p-name"><?php echo $fullname; ?></li>
                 	<li class="p-info">
                    	<img src="<?php echo URL; ?>public/images/geo.png" style="margin:1px 3px 0 0; float:left;" /><?php echo $location; ?>
                    </li>
                </ul>
            </div>
          	
            <div style="clear:both;"></div>
              
            <div id="carousel" class="es-carousel-wrapper" >
            		<span>
                    <a alt='Favorite' class='remote-buttons' title='Your favorites' id='heart-show' href='#' style='margin:0 4px 0 0; float:left;' >Fave</a>
					<?php echo $no_fave; ?> Favorites</span>
					<div class="es-carousel">
						<ul class="ec">
							<?php 
								if(!empty($user_fave)){
									foreach($user_fave as $s_id)
									{
										
										$votes = $show->count_vote($s_id);
										$options = $show->user_options($s_id);
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
												$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Remove from your favourites' id='faveselected' href='#$s_id'>Fave</a></li>";
											}else{
												$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Add to your favourites' id='fave' href='#$s_id'>Fave</a></li>";					
											}
											
											if(!empty($uw_id)){
												$watching = "<li><a alt='$show_name' class='remote-buttons' title='Remove from currently watching' id='watchselected' href='#$s_id'>Watching</a></li>";
											}else{
												$watching = "<li><a alt='$show_name' class='remote-buttons' title='Add to currently watching' id='watching' href='#$s_id'>Watching</a></li>";
											}
											
											if(!empty($uv_id)){
												$like     = "<li><a alt='$show_name' class='remote-buttons' title='Do not like it anymore?' id='dislike' href='#$s_id'>Disike it</a></li>";
											}else{
												$like     = "<li><a alt='$show_name' class='remote-buttons' title='Like it?' id='like' href='#$s_id'>Like it</a></li>";
											}
										}
										
										$vote_no = "<a id='circle' href='".URL."show/view/$s_id'><p>$votes</p></a>";
										echo "<li class='show-thumb'>
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
								  			</li>";
									} 
								}
							?>
						</ul>
					</div>
			</div>
           
            <div id="carousel2" class="es-carousel-wrapper">
             <span><?php echo $no_watch; ?>
             <a alt='Currently Watching' class='remote-buttons' title='Your watching' id='eye-show' href='#' style='margin:0 4px 0 0;float:left;' >Watching</a>
              Currently Watching
             </span>
					<div class="es-carousel">
						<ul class="ec">
							<?php 
								if(!empty($user_watch)){
									foreach($user_watch as $s_id)
									{
										$votes = $show->count_vote($s_id);
										$options = $show->user_options($s_id);
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
												$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Remove from your favourites' id='faveselected' href='#$s_id'>Fave</a></li>";
											}else{
												$fave     = "<li><a alt='$show_name'  class='remote-buttons' title='Add to your favourites' id='fave' href='#$s_id'>Fave</a></li>";					
											}
											
											if(!empty($uw_id)){
												$watching = "<li><a alt='$show_name' class='remote-buttons' title='Remove from currently watching' id='watchselected' href='#$s_id'>Watching</a></li>";
											}else{
												$watching = "<li><a alt='$show_name' class='remote-buttons' title='Add to currently watching' id='watching' href='#$s_id'>Watching</a></li>";
											}
											
											if(!empty($uv_id)){
												$like     = "<li><a alt='$show_name' class='remote-buttons' title='Do not like it anymore?' id='dislike' href='#$s_id'>Disike it</a></li>";
											}else{
												$like     = "<li><a alt='$show_name' class='remote-buttons' title='Like it?' id='like' href='#$s_id'>Like it</a></li>";
											}
										}
										
										$vote_no = "<a id='circle' href='".URL."show/view/$s_id'><p>$votes</p></a>";
										echo "<li class='show-thumb'>
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
								  			</li>";
									} 
								}
							?>
						</ul>
					</div>
			</div>
            
            
		</div>
	</div>
<script type="text/javascript">
	  $('#carousel, #carousel2').elastislide({
		  imageW 	: 120,
		  margin	: 45
	  });
	  
	
</script>
<script>
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
