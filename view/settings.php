	<div id="wrapper">
		<div id="header">
			<a href="/" id="logo" ></a>
			<?php include ('nav.php'); ?>
		</div>
         <?php include('feedback.php');?>
		<?php include ('filter-settings.php'); ?>
		<?php include ('tv-rack.php'); ?>
		<span id='loading'> </span>
		<div id="main">
			<div class="shows">
				<div id="container"></div>
			</div>
		</div>
	</div>		
</body>
<script src="<?php echo URL;?>public/js/pagination-s.js" type="text/javascript"></script>