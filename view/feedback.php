<?php 
	if(isset($_SESSION['msg'])){
		echo "<div id='feedback'>".$_SESSION['msg']."</div>";
		echo "<script> setTimeout(function() {
						  $('#feedback').fadeOut(function() { $(this).text('');  });
					   }, 5000);</script>";
		unset($_SESSION['msg']);
	}else{
		echo "<div id='feedback' style='display:none;'></div>";
	}
?>